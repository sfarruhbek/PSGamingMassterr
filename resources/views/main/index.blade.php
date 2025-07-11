@extends('layouts.main')
@section('content')

    <div class="content-wrapper">
        <!-- Контент -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-primary" onclick="sellProduct(null)">
                    <i class="bx bx-cart"></i> Продажа товара
                </button>
            </div>

            <div id="types_card">
                {{-- Контент будет отображаться здесь --}}
            </div>
        </div>
    </div>



    <script>
        function completeCard(data){
            let jsonData = encodeURIComponent(JSON.stringify(data));
            let name = data.name;
            let timeStart = data.active_histories[0]?.started_at ?? null;
            console.log(timeStart);
            let user_count = data.active_histories.length;
            return `
                <div class="col-md-6 col-xl-4" id="card-${data.id}">
                    <div class="card cursor-pointer bg-secondary text-white mb-3">
                        <div class="card-header">${name}</div>
                        <div class="card-body">
                            <h5 class="card-title text-white card-time" data-start-time="${timeStart ?? ''}">Время: 00:00</h5>
                            <h5 class="card-title text-white">Количество пользователей: ${user_count}</h5>

                            <div class="d-flex justify-content-between">
                                <button class="btn btn-success" onclick="startDevice(JSON.parse(decodeURIComponent('${jsonData}')))">Добавить</button>

                                <button class="btn btn-primary" onclick="sellProduct(JSON.parse(decodeURIComponent('${jsonData}')).id)">Продажа товара</button>

                                <button class="btn btn-warning" onclick="finishDevice(JSON.parse(decodeURIComponent('${jsonData}')))">Завершить</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        function startCard(data){
            let jsonData = encodeURIComponent(JSON.stringify(data));
            return `
                        <div class="col-md-6 col-xl-4" id="card-${data.id}">
                            <div class="card cursor-pointer bg-info text-white mb-3">
                                <div class="card-header">${data.name}</div>
                                <div class="card-body">
                                    <h5 class="card-title text-white card-time">Время: 00:00</h5>
                                    <h5 class="card-title text-white card-user-count">Количество пользователей: 0</h5>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary" onclick="startDevice(JSON.parse(decodeURIComponent('${jsonData}')))">Начать</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
        }

        function updateCard(card_id, data) {
            const card = document.getElementById(card_id);
            if (!card) return;

            let newCardHtml = "";
            if(data.active_histories.length == 0){
                newCardHtml += startCard(data);
            }else{
                newCardHtml += completeCard(data);
            }
            card.outerHTML = newCardHtml;
        }
        setInterval(() => {
            document.querySelectorAll('.card-time[data-start-time]').forEach(el => {
                const start = el.getAttribute('data-start-time');
                if (!start) return;
                const startTime = new Date(start);
                const now = new Date();
                let diff = Math.floor((now - startTime) / 1000);
                if (diff < 0) diff = 0;
                const hours = String(Math.floor(diff / 3600)).padStart(2, '0');
                const minutes = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
                const seconds = String(diff % 60).padStart(2, '0');
                el.textContent = `Время: ${hours}:${minutes}:${seconds}`;
            });
        }, 1000);

    </script>
    <script>
        let data = @json($data);
        let types_card = document.getElementById('types_card');

        data.forEach(value => {
            let contentHtml = "";
            value.devices.forEach(val => {
                if(val.active_histories.length == 0){
                    contentHtml += startCard(val);
                }else{
                    contentHtml += completeCard(val);
                }
            });
            addHtml = `
                <div class="mb-5">
                    <h1 class="pb-1 mb-4">${value.name}</h1>
                    <div class="row">

                        ${contentHtml}

                    </div>
                </div>
             `
            types_card.innerHTML += addHtml;
        });
    </script>

    <script>
        function startDevice(data) {
            Swal.fire({
                title: `${data.name}`,
                html: `
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <div>
                            <label for="userCount" style="font-weight: bold;">Количество пользователей</label><br>
                            <input id="userCount" type="number" min="1" class="swal2-input" placeholder="Masalan: 3" style="width: 80%;">
                        </div>
                        <div style="display: none">
                            <label for="usageType" style="font-weight: bold;">Тип использования</label><br>
                            <select id="usageType" class="swal2-input" style="width: 80%;">
                                <option value="">Выберите</option>
                                <option value="easy" selected>Легкий</option>
                                <option value="hard">Тяжелый</option>
                            </select>
                        </div>
                    </div>
                        `,
                showCancelButton: true,
                confirmButtonText: "Начать",
                cancelButtonText: "Отмена",
                preConfirm: () => {
                    const userCount = parseInt(document.getElementById("userCount").value);
                    const usageType = document.getElementById("usageType").value;
                    if (!userCount || userCount <= 0) {
                        Swal.showValidationMessage("Количество пользователей noto‘g‘ri");
                        return false;
                    }
                    if (!usageType) {
                        Swal.showValidationMessage("Тип использованияni tanlang");
                        return false;
                    }
                    return { user_count: userCount, usage_type: usageType };
                }
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/res/device/start/${data.id}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify(result.value)
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => { throw err; });
                            }
                            return response.json();
                        })
                        .then(data => {
                            updateCard(`card-${data.device.id}`, data.device);
                            Swal.fire("Boshladi!", data.message, "success").then(function (){
                                location.reload();
                            })
                        })
                        .catch(error => {
                            Swal.fire("Ошибка", error.message || "Ошибка yuz berdi", "error");
                            console.error(error);
                        });
                }
            });
        }

        function finishDevice(data) {
            const histories = data.active_histories || [];
            const productHistories = data.device_product_history_active || [];
            const userCount = histories.length;

            const total = calculateSimpleTotal(histories, data.type, productHistories);
            let totalProductSum = productHistories.reduce((sum, ph) => sum + parseFloat(ph.sold), 0);
            let totalUsersSum = total - productHistories.reduce((sum, ph) => sum + parseFloat(ph.sold), 0);

            const priceFields = userCount > 2
                ? { easy: data.type.price21, hard: data.type.price22 }
                : { easy: data.type.price11, hard: data.type.price12 };

            let paidPrices = [];

            const usersHtml = histories.map((h, idx) => {
                const startTime = new Date(h.started_at);
                const now = new Date();
                const diffMinutes = Math.floor((now - startTime) / 60000);
                const usageType = h.use;
                const pricePerHour = usageType === "easy" ? priceFields.easy : priceFields.hard;
                const totalPrice = pricePerHour ? (diffMinutes * (pricePerHour / 60)).toFixed(0) : "-";


                if (pricePerHour) paidPrices.push(parseFloat(totalPrice));

                const allowRemove = histories.length > 1;
                const removeBtn = allowRemove
                    ? `<button class="swal2-confirm swal2-styled" style="margin-left:10px;" onclick="finishSingleUser(${data.id}, ${h.id}, this)">-</button>`
                    : '';

                return `
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span>
                    <b>Пользователь #${idx + 1}</b><br>
                    Времяi: ${diffMinutes} minut<br>
                </span>
            </div>
        `;
            }).join('');

            const totalRow = userCount > 0
                ? `
                <div style="margin-top:10px; font-weight:bold; text-align:right;">
                    Общая сумма пользователей: ${totalUsersSum.toFixed(2)} сум
                </div>

                <div style="margin-top:10px; font-weight:bold; text-align:right;">
                    Общая сумма товаров: ${totalProductSum.toFixed(2)} сум
                </div>

                <div style="margin-top:10px; font-weight:bold; text-align:right;">
                    Общая сумма: ${total.toFixed(2)} сум
                </div>
                <div class="mb-3 row justify-content-end" style="margin-top:10px;">
                    <label for="paidAmount" class="col-form-label col-auto fw-bold text-end">К оплате:</label>
                    <div class="col-auto">
                        <input type="number" class="form-control text-end" id="paidAmount" placeholder="Введите сумму  " style="width: 200px;" />
                    </div>
                    <div class="col-auto align-self-center">сум</div>
                </div>
                `
                : '';




            const products = productHistories.map(ph => ({
                product_id: ph.product_id,
                count: ph.count,
                sold: ph.sold
            }));

            const productsHtml = productHistories.length > 0
                ? `
            <div style="margin-top:20px; border-top:1px solid #ccc; padding-top:10px;">
                <h5><b>📦 Товары:</b></h5>
                ${productHistories.map(ph => `
                    <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                        <span>${ph.product?.name || '(Неизвестный товар)'} (${ph.count} dona)</span>
                        <span>${parseFloat(ph.sold)} сум</span>
                    </div>
                `).join('')}
            </div>
        `
                : '';

            const finishAllBtn = userCount > 0
                ? `<button id="finish-all-btn" class="swal2-confirm swal2-styled" style="flex:1; margin-right:8px;">Завершить всё</button>`
                : '';
            const cancelBtn = `<button id="custom-cancel-btn" class="swal2-cancel swal2-styled" style="flex:1;">Отмена</button>`;

            const buttonRow = `
        <div style="display:flex; gap:0.5rem; margin-top:10px;">
            ${cancelBtn}${finishAllBtn}
        </div>
    `;




            Swal.fire({
                title: "Общий отчет",
                html: (usersHtml || "Пользователь topilmadi") + productsHtml + totalRow + buttonRow,
                showCancelButton: false,
                showConfirmButton: false,
                didOpen: () => {
                    const allBtn = document.getElementById('finish-all-btn');
                    let sold_cost_input = document.getElementById('paidAmount');
                    let sold_cost = 0;

                    sold_cost_input.addEventListener('input', () => {
                        sold_cost = Number(sold_cost_input.value)
                    });
                    if (allBtn) {
                        allBtn.addEventListener('click', function () {
                            const combinedData = {
                                prices: paidPrices,
                                paid_prices: sold_cost,
                                products: products
                            };
                            finishAllUsers(data.id, combinedData); // ✅ data obyekt tarzida yuboriladi
                        });
                    }
                    document.getElementById('custom-cancel-btn').addEventListener('click', function () {
                        Swal.close();
                    });
                }
            });
        }


        function calculateSimpleTotal(histories, type, productHistories = []) {
            const now = new Date();
            const userCount = histories?.length || 0;

            const priceFields = userCount > 2
                ? { easy: type.price21, hard: type.price22 }
                : { easy: type.price11, hard: type.price12 };

            let total = 0;

            // Пользовательlar narxi

            if (histories && histories.length > 0) {
                let h = histories[0];

                let start = new Date(h.started_at);
                let end = h.ended_at ? new Date(h.ended_at) : now;
                let minutes = Math.floor((end - start) / 60000);

                if(histories.length < 3) {
                    let pricePerHour = h.use === "easy" ? priceFields.easy : priceFields.hard;
                    if (pricePerHour) {
                        total += minutes * (pricePerHour / 60);
                    }
                } else {
                    let h2 = histories[2];
                    const start2 = new Date(h.started_at);
                    const end2 = h.ended_at ? new Date(h.ended_at) : now;
                    const minutes2 = Math.floor((end2 - start2) / 60000);

                    minutes = minutes - minutes2;
                    let pricePerHour = h.use === "easy" ? priceFields.easy : priceFields.hard;
                    let pricePerHour2 = h2.use === "easy" ? priceFields.easy : priceFields.hard;
                    if (pricePerHour) {
                        total += (minutes * (pricePerHour / 60)) + (minutes2 * (pricePerHour2 / 60));
                    }
                }


            }

            if (productHistories && productHistories.length > 0) {
                productHistories.forEach(ph => {
                    const sold = parseFloat(ph.sold);
                    if (!isNaN(sold)) {
                        total += sold;
                    }
                });
            }

            return total;
        }


        function calculateTotalSum(histories, type) {
            const minuteMap = new Map();
            const now = new Date();

            // 1. Har bir historyni minutlar oralig'ida belgilaymiz
            histories.forEach((history, index) => {
                const start = new Date(history.started_at);
                const end = history.finished_at ? new Date(history.finished_at) : now;

                const startMin = Math.floor(start.getTime() / 60000);
                const endMin = Math.floor(end.getTime() / 60000);

                for (let i = startMin; i < endMin; i++) {
                    if (!minuteMap.has(i)) {
                        minuteMap.set(i, []);
                    }
                    minuteMap.get(i).push(index);
                }
            });

            // 2. Har bir foydalanuvchining k va n minutlarini hisoblaymiz
            const userTimeMap = new Map(); // index -> { k, n }
            for (let [minute, users] of minuteMap.entries()) {
                users.forEach(index => {
                    if (!userTimeMap.has(index)) {
                        userTimeMap.set(index, { k: 0, n: 0 });
                    }
                    const overlap = users.length;
                    if (overlap >= 3) {
                        userTimeMap.get(index).k++;
                    } else {
                        userTimeMap.get(index).n++;
                    }
                });
            }

            // 3. Har bir foydalanuvchi uchun narxni hisoblaymiz
            const userSums = [];
            let total = 0;

            userTimeMap.forEach((time, index) => {
                const history = histories[index];
                const use = history.use;
                let sum = 0;

                if (use === "easy") {
                    sum = time.k * type.price21 + time.n * type.price11;
                } else if (use === "hard") {
                    sum = time.k * type.price22 + time.n * type.price12;
                }

                userSums[index] = sum;
                total += sum;
            });

            return { total, userSums };
        }


        function finishSingleUser(deviceId, historyId, btn) {
            return false;
            btn.disabled = true;

            // Find the user's price from the UI or recalculate here
            // Example: get price from DOM or recalculate as in your usersHtml
            const userDiv = btn.closest('div');
            const priceText = userDiv.querySelector('span').innerHTML.match(/Цена: ([\d.]+)/);
            const paidPrice = priceText ? parseFloat(priceText[1]) : 0;

            fetch(`/res/device/finish/${deviceId}/${historyId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({ paid_price: paidPrice })
            })
                .then(response => {
                    if (!response.ok) throw new Error("Ошибка yuz berdi");
                    return response.json();
                })
                .then(data => {
                    Swal.fire("Yakunlandi!", "Пользователь yakunlandi", "success")
                        .then(() => {
                            window.location.reload();
                        });
                })
                .catch(error => {
                    Swal.fire("Ошибка", error.message, "error");
                    btn.disabled = false;
                });
        }

        function finishAllUsers(deviceId, data) {
            // So‘rov yuborish
            fetch(`/res/device/finish-all/${deviceId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({
                    prices: data.prices || [],
                    paid_prices: data.paid_prices,
                    products: data.products || []
                })
            })
                .then(response => {
                    if (!response.ok) throw new Error("Ошибка yuz berdi");
                    return response.json();
                })
                .then(data => {
                    Swal.fire("Yakunlandi!", "Barcha foydalanuvchilar va mahsulotlar yakunlandi", "success")
                        .then(() => {
                            window.location.reload();
                        });
                })
                .catch(error => {
                    Swal.fire("Ошибка", error.message, "error");
                });
        }


    </script>


    <script>
        let oldProductHtml = "";
        function sellProduct(data_id) {
            let productList = [];

            let DDevice = null;

            data.forEach(val => {
                val['devices'].forEach(k => {
                    if(k['id'] === data_id){
                        DDevice = k;
                    }
                });
            });
            console.log(DDevice);

            if(DDevice) {
                DDevice['device_product_history_active'].forEach(val => {
                    let name = `${val['product']['name']}(${parseInt(val['product']['expense']).toLocaleString()} so'm)`
                    productList.push({
                        num: productList.length,
                        product_id: val['product']['id'],
                        name: name,
                        count: val['count'],
                        expense: val['sold'] / val['count'],
                        product_history_id: val['id'],
                    });
                });
            }


            function calculateTotalSum() {
                return productList.reduce((sum, p) => {
                    const expense = parseFloat(p.expense || 0);
                    return sum + expense * p.count;
                }, 0);
            }


            function renderProductListHTML() {
                const total = calculateTotalSum();
                const items = productList.map(p => `
                    <li>
                        ${p.name} - ${p.count} dona
                        <button class="editProductBtn btn btn-warning bx bx-pencil"
                                type="button"
                                data-data='${JSON.stringify(p).replace(/'/g, "&apos;")}'>
                        </button>
                    </li>
                `).join('');
                return `
            ${items}
            <hr>
            <b>Umumiy summa: ${total.toLocaleString()} so'm</b>
                `;
            }

            function openMainModal() {
                Swal.fire({
                    title: 'Товары',
                    html: `
                <ul id="productList" style="text-align: left; padding-left: 20px; margin-bottom: 20px;">
                    ${renderProductListHTML()}
                </ul>
                <button type="button" id="addProductBtn" class="btn btn-success">+ Добавить товар</button>
            `,
                    showCancelButton: true,
                    confirmButtonText: 'Отправить',
                    didOpen: () => {
                        document.querySelectorAll('.editProductBtn').forEach(button => {
                            button.addEventListener('click', function() {
                                const dataValue = this.getAttribute('data-data');
                                const jsonData = JSON.parse(dataValue);
                                openEditProductModal(jsonData);
                            });
                        });
                        document.getElementById('addProductBtn').addEventListener('click', () => {
                            openAddProductModal();
                        });
                    },
                    preConfirm: () => {
                        if (productList.length === 0) {
                            Swal.showValidationMessage('Hech qanday mahsulot qo‘shilmadi');
                            return false;
                        }
                        return true;
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        const payload = {
                            device_id: data_id,
                            products: productList
                        };

                        fetch('/res/sell-product', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(payload)
                        })
                            .then(res => res.json())
                            .then(res => {
                                Swal.fire('Успешно', 'Товары sotildi.', 'success')
                                    .then(() => location.reload());
                            })
                            .catch(err => {
                                Swal.fire('Ошибка', 'Ошибка подключения к серверу yuz berdi.', 'error');
                                console.error(err);
                            });
                    }
                });
            }

            function openAddProductModal() {
                Swal.fire({
                    title: 'Добавить товар',
                    html: `
                <div style="display: flex; gap: 10px; align-items: center;">
                    <select id="productSelect" style="width: 60%" class="form-control">
                        <option value="">Выберите товар...</option>
                    </select>
                    <input type="number" id="productCount" min="0" placeholder="Количество" class="form-control" style="width: 30%">
                </div>
            `,
                    confirmButtonText: 'Добавить',
                    showCancelButton: true,
                    didOpen: () => {
                        $('#productSelect').select2({
                            dropdownParent: $('.swal2-container'),
                            placeholder: 'Выберите товар...',
                            dropdownCssClass: 'select2-scrollable-dropdown',
                            ajax: {
                                url: '/api/products',
                                dataType: 'json',
                                delay: 250,
                                data: params => ({ term: params.term }),
                                processResults: data => ({
                                    results: data.map(p => ({
                                        id: p.id,
                                        text: `${p.name}(${parseInt(p.expense).toLocaleString()} so'm)`,
                                        count: p.count,
                                        expense: p.expense
                                    }))
                                }),
                                cache: true
                            }
                        });

                        $('#productSelect').on('select2:select', function (e) {
                            const selected = e.params.data;
                            const maxCount = selected.count;
                            const input = document.getElementById('productCount');
                            input.max = maxCount;
                            input.placeholder = `Макс: ${maxCount}`;
                            if (parseInt(input.value) > maxCount) {
                                input.value = maxCount;
                            }
                        });
                    },
                    preConfirm: () => {
                        const productId = $('#productSelect').val();
                        const productName = $('#productSelect option:selected').text();
                        const count = parseInt($('#productCount').val());
                        const max = parseInt(document.getElementById('productCount').max);
                        const selected = $('#productSelect').select2('data')[0];
                        const expense = selected?.expense || 0;

                        if (!productId || !count || count <= 0 || count > max) {
                            Swal.showValidationMessage(`Количество noto‘g‘ri: 1 dan ${max} gacha kiriting`);
                            return false;
                        }

                        productList.push({
                            num: productList.length,
                            product_id: productId,
                            name: productName,
                            count: count,
                            expense: expense,
                            product_history_id: 0
                        });

                        return true;
                    }
                }).then(res => {
                    if (res.isConfirmed) {
                        openMainModal();
                    }
                });
            }

            function openEditProductModal(d) {
                console.log(d);
                Swal.fire({
                    title: d.name,
                    html: `
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input type="number" id="productCount" min="1" value="${d.count}" placeholder="Количество" class="form-control" style="width: 100%">
                        </div>
                    `,
                    confirmButtonText: 'Обновить',
                    showCancelButton: true,
                    preConfirm: () => {
                        const productId = d.num;
                        const count = parseInt(document.getElementById('productCount').value);

                        productList.forEach(vv => {
                            if (vv.num === productId) {
                                vv.count = count;
                            }
                        });
                        console.log(productId + " - " + count);
                        console.log(productList);

                        return true;
                    }
                }).then(res => {
                    if (res.isConfirmed) {
                        openMainModal(); // Asosiy modalni ochish
                    }
                });
            }

            openMainModal();
        }

    </script>
@endsection
