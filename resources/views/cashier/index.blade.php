@extends('layouts.cashier')
@section('content')

    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y" id="types_card">
            {{--            --}}
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
                            <h5 class="card-title text-white card-time" data-start-time="${timeStart ?? ''}">Vaqt: 00:00</h5>
                            <h5 class="card-title text-white">Foydalanuvchilar soni: ${user_count}</h5>

                            <div class="d-flex justify-content-between">
                                <button class="btn btn-success" onclick="startDevice(JSON.parse(decodeURIComponent('${jsonData}')))">Qo‘shish</button>

                                <button class="btn btn-primary" onclick="sellProduct(JSON.parse(decodeURIComponent('${jsonData}')))">Mahsulot sotish</button>

                                <button class="btn btn-warning" onclick="finishDevice(JSON.parse(decodeURIComponent('${jsonData}')))">Yakunlash</button>
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
                                    <h5 class="card-title text-white card-time">Vaqt: 00:00</h5>
                                    <h5 class="card-title text-white card-user-count">Foydalanuvchilar soni: 0</h5>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary" onclick="startDevice(JSON.parse(decodeURIComponent('${jsonData}')))">Boshlash</button>
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
                el.textContent = `Vaqt: ${hours}:${minutes}:${seconds}`;
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
                            <label for="userCount" style="font-weight: bold;">Foydalanuvchilar soni</label><br>
                            <input id="userCount" type="number" min="1" class="swal2-input" placeholder="Masalan: 3" style="width: 80%;">
                        </div>
                        <div>
                            <label for="usageType" style="font-weight: bold;">Foydalanish turi</label><br>
                            <select id="usageType" class="swal2-input" style="width: 80%;">
                                <option value="">Tanlang</option>
                                <option value="easy">Yengil</option>
                                <option value="hard">Og'ir</option>
                            </select>
                        </div>
                    </div>
                        `,
                showCancelButton: true,
                confirmButtonText: "Boshlash",
                cancelButtonText: "Bekor qilish",
                preConfirm: () => {
                    const userCount = parseInt(document.getElementById("userCount").value);
                    const usageType = document.getElementById("usageType").value;
                    if (!userCount || userCount <= 0) {
                        Swal.showValidationMessage("Foydalanuvchilar soni noto‘g‘ri");
                        return false;
                    }
                    if (!usageType) {
                        Swal.showValidationMessage("Foydalanish turini tanlang");
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
                            Swal.fire("Xatolik", error.message || "Xatolik yuz berdi", "error");
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
                    <b>Foydalanuvchi #${idx + 1}</b><br>
                    Vaqti: ${diffMinutes} minut<br>
                    Narxi: ${totalPrice} so'm
                </span>
                ${removeBtn}
            </div>
        `;
            }).join('');

            const totalRow = userCount > 0
                ? `<div style="margin-top:10px; font-weight:bold; text-align:right;">Umumiy summa: ${total.toFixed(2)} so'm</div>`
                : '';

            const products = productHistories.map(ph => ({
                product_id: ph.product_id,
                count: ph.count,
                sold: ph.sold
            }));

            const productsHtml = productHistories.length > 0
                ? `
            <div style="margin-top:20px; border-top:1px solid #ccc; padding-top:10px;">
                <h5><b>📦 Mahsulotlar:</b></h5>
                ${productHistories.map(ph => `
                    <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                        <span>${ph.product?.name || '(Nomaʼlum mahsulot)'} (${ph.count} dona)</span>
                        <span>${parseFloat(ph.sold * ph.count)} so'm</span>
                    </div>
                `).join('')}
            </div>
        `
                : '';

            const finishAllBtn = userCount > 0
                ? `<button id="finish-all-btn" class="swal2-confirm swal2-styled" style="flex:1; margin-right:8px;">Umumiy yakunlash</button>`
                : '';
            const cancelBtn = `<button id="custom-cancel-btn" class="swal2-cancel swal2-styled" style="flex:1;">Bekor qilish</button>`;

            const buttonRow = `
        <div style="display:flex; gap:0.5rem; margin-top:10px;">
            ${cancelBtn}${finishAllBtn}
        </div>
    `;

            const combinedData = {
                paid_prices: paidPrices,
                products: products
            };

            Swal.fire({
                title: "Umumiy hisobot",
                html: (usersHtml || "Foydalanuvchi topilmadi") + productsHtml + totalRow + buttonRow,
                showCancelButton: false,
                showConfirmButton: false,
                didOpen: () => {
                    const allBtn = document.getElementById('finish-all-btn');
                    if (allBtn) {
                        allBtn.addEventListener('click', function () {
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

            // Foydalanuvchilar narxi
            if (histories && histories.length > 0) {
                histories.forEach(h => {
                    const start = new Date(h.started_at);
                    const end = h.ended_at ? new Date(h.ended_at) : now;
                    const minutes = Math.floor((end - start) / 60000);
                    const pricePerHour = h.use === "easy" ? priceFields.easy : priceFields.hard;
                    if (pricePerHour) {
                        total += minutes * (pricePerHour / 60);
                    }
                });
            }

            // Mahsulotlar narxi
            if (productHistories && productHistories.length > 0) {
                productHistories.forEach(ph => {
                    const sold = parseFloat(ph.sold * ph.count);
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
            btn.disabled = true;

            // Find the user's price from the UI or recalculate here
            // Example: get price from DOM or recalculate as in your usersHtml
            const userDiv = btn.closest('div');
            const priceText = userDiv.querySelector('span').innerHTML.match(/Narxi: ([\d.]+)/);
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
                    if (!response.ok) throw new Error("Xatolik yuz berdi");
                    return response.json();
                })
                .then(data => {
                    Swal.fire("Yakunlandi!", "Foydalanuvchi yakunlandi", "success")
                        .then(() => {
                            window.location.reload();
                        });
                })
                .catch(error => {
                    Swal.fire("Xatolik", error.message, "error");
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
                    paid_prices: data.paid_prices || [],
                    products: data.products || []
                })
            })
                .then(response => {
                    if (!response.ok) throw new Error("Xatolik yuz berdi");
                    return response.json();
                })
                .then(data => {
                    Swal.fire("Yakunlandi!", "Barcha foydalanuvchilar va mahsulotlar yakunlandi", "success")
                        .then(() => {
                            window.location.reload();
                        });
                })
                .catch(error => {
                    Swal.fire("Xatolik", error.message, "error");
                });
        }


    </script>


    <script>
        function sellProduct(data) {
            let productList = [];

            function renderProductListHTML() {
                return productList.map(p => `<li>${p.name} - ${p.count} dona</li>`).join('');
            }

            function openMainModal() {
                Swal.fire({
                    title: 'Mahsulotlar ro‘yxati',
                    html: `
                <ul id="productList" style="text-align: left; padding-left: 20px; margin-bottom: 20px;">
                    ${renderProductListHTML()}
                </ul>
                <button type="button" id="addProductBtn" class="btn btn-success">+ Yangi mahsulot qo‘shish</button>
            `,
                    showCancelButton: true,
                    confirmButtonText: 'Yuborish',
                    didOpen: () => {
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
                            device_id: data.id,
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
                                Swal.fire('Muvaffaqiyatli', 'Mahsulotlar sotildi.', 'success')
                                    .then(() => location.reload());
                            })
                            .catch(err => {
                                Swal.fire('Xatolik', 'Server bilan ulanishda xatolik yuz berdi.', 'error');
                                console.error(err);
                            });
                    }
                });
            }

            function openAddProductModal() {
                Swal.fire({
                    title: 'Mahsulot qo‘shish',
                    html: `
                <div style="display: flex; gap: 10px; align-items: center;">
                    <select id="productSelect" style="width: 60%" class="form-control">
                        <option value="">Mahsulotni tanlang...</option>
                    </select>
                    <input type="number" id="productCount" min="1" placeholder="Soni" class="form-control" style="width: 30%">
                </div>
            `,
                    confirmButtonText: 'Qo‘shish',
                    showCancelButton: true,
                    didOpen: () => {
                        $('#productSelect').select2({
                            dropdownParent: $('.swal2-container'),
                            placeholder: 'Mahsulotni tanlang...',
                            minimumInputLength: 1,
                            ajax: {
                                url: '/api/products',
                                dataType: 'json',
                                delay: 250,
                                data: params => ({ term: params.term }),
                                processResults: data => ({
                                    results: data.map(p => ({
                                        id: p.id,
                                        text: `${p.name} (${p.count} ta) - ${parseInt(p.expense).toLocaleString()} so'm`, // ✅ expense
                                        count: p.count,
                                        expense: p.expense // ✅ narxni ham saqlaymiz
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
                            input.placeholder = `Maks: ${maxCount}`;
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

                        if (!productId || !count || count <= 0 || count > max) {
                            Swal.showValidationMessage(`Soni noto‘g‘ri: 1 dan ${max} gacha kiriting`);
                            return false;
                        }

                        productList.push({
                            product_id: productId,
                            name: productName,
                            count: count
                        });

                        return true;
                    }
                }).then(res => {
                    if (res.isConfirmed) {
                        openMainModal();
                    }
                });
            }

            openMainModal();
        }


    </script>
@endsection
