@extends('layouts.main')
@section('content')

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Основная таблица -->
            <div class="card">
                <h5 class="card-header">Типы</h5>
                <form method="GET" action="" class="d-flex m-2" style="max-width: 400px; width: 100%;">
                    <input type="text" name="search" class="form-control mr-2" placeholder="Qidiruv..." value="{{ request()->search }}">
                    <button type="submit" class="btn btn-primary">Qidirish</button>
                </form>

                <div class="text-end">
                    <button onclick="addType()" class="btn btn-primary m-2">Добавить</button>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table" style="margin-bottom: 90px">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>Название</th>
                            <th>Цена</th>
                            <th>Количество</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        <?php $nm = 1; ?>
                        @foreach($data as $val)
                            <tr>
                                <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{$nm++}}</strong></td>
                                <td>{{$val->name}}</td>
                                <td>
                                    2: {{$val->price11}} сум<br>
                                    4: {{$val->price21}} сум
                                </td>
                                <td>{{count($val->devices)}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a onclick="editType({{$val}})" class="dropdown-item" href="javascript:void(0);">
                                                <i class="bx bx-edit-alt me-1"></i> Редактировать
                                            </a>
                                            <a onclick="deleteType({{$val->id}})" class="dropdown-item text-danger" href="javascript:void(0);">
                                                <i class="bx bx-trash me-1"></i> Удалить
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination linklari --}}
                <div class="mt-3 d-flex justify-content-center">
                    {{ $data->links('pagination::bootstrap-4') }}
                </div>
            </div>
            <!-- / Основная таблица -->

        </div>
        <!-- / Content -->

        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->

    <script>
        function addType() {
            Swal.fire({
                title: "Добавить тип",
                html: `
            <div style="text-align: left;">
                <div style="margin-bottom: 15px;">
                    <label for="name">Название</label><br>
                    <input id="name" type="text" placeholder="Название типа" style="width: 100%; padding: 8px; margin-top: 5px;">
                </div>

                <div style="margin-bottom: 10px;">
                    <strong>Цены до 2 пультов:</strong>
                    <div style="display: flex; gap: 10px; margin-top: 5px;">
                        <div style="flex: 1;">
                            <input id="price11" type="number" placeholder="Введите цену..." style="width: 100%; padding: 8px;">
                        </div>
                    </div>
                </div>

                <div>
                    <strong>Цены свыше 2 пультов:</strong>
                    <div style="display: flex; gap: 10px; margin-top: 5px;">
                        <div style="flex: 1;">
                            <input id="price21" type="number" placeholder="Введите цену..." style="width: 100%; padding: 8px;">
                        </div>
                    </div>
                </div>
            </div>
        `,
                showCancelButton: true,
                confirmButtonText: "Сохранить",
                cancelButtonText: "Отмена",
                preConfirm: () => {
                    const name = document.getElementById("name").value.trim();
                    const price11 = document.getElementById("price11").value;
                    const price21 = document.getElementById("price21").value;

                    if (!name || !price11 || !price21) {
                        Swal.showValidationMessage("Пожалуйста, заполните все поля");
                        return false;
                    }

                    return { name, price11, price21 };
                }
            }).then(result => {
                if (result.isConfirmed) {
                    fetch("{{route('res.type.store')}}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify(result.value)
                    })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                title: "Сохранено!",
                                text: "Новый тип успешно добавлен.",
                                icon: "success",
                                confirmButtonText: "ОК"
                            }).then(result => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        })
                        .catch(error => {
                            Swal.fire("Ошибка", "Ошибка подключения к серверу", "error");
                            console.error(error);
                        });
                }
            });
        }
    </script>

    <script>
        function editType(type) {
            Swal.fire({
                title: "Редактировать тип",
                html: `
            <div style="text-align: left;">
                <div style="margin-bottom: 15px;">
                    <label for="name">Название</label><br>
                    <input id="name" type="text" value="${type.name}" placeholder="Название типа" style="width: 100%; padding: 8px;">
                </div>

                <div style="margin-bottom: 10px;">
                    <strong>Цены для 2 пультов:</strong>
                    <div style="display: flex; gap: 10px;">
                        <div style="flex: 1;">
                            <input id="price11" type="number" value="${type.price11}" placeholder="Цена" style="width: 100%; padding: 8px;">
                        </div>
                    </div>
                </div>

                <div>
                    <strong>Цены свыше 2 пультов:</strong>
                    <div style="display: flex; gap: 10px;">
                        <div style="flex: 1;">
                            <input id="price21" type="number" value="${type.price21}" placeholder="Цена" style="width: 100%; padding: 8px;">
                        </div>
                    </div>
                </div>
            </div>
        `,
                showCancelButton: true,
                confirmButtonText: "Обновить",
                cancelButtonText: "Отмена",
                preConfirm: () => {
                    const updated = {
                        name: document.getElementById("name").value.trim(),
                        price11: document.getElementById("price11").value,
                        price21: document.getElementById("price21").value,
                    };

                    if (!updated.name || !updated.price11 || !updated.price21) {
                        Swal.showValidationMessage("Пожалуйста, заполните все поля");
                        return false;
                    }

                    return updated;
                }
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/res/type/${type.id}`, {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify(result.value)
                    })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire("Обновлено!", "Информация о типе успешно обновлена.", "success")
                                .then(() => location.reload());
                        })
                        .catch(error => {
                            Swal.fire("Ошибка", "Ошибка подключения к серверу", "error");
                            console.error(error);
                        });
                }
            });
        }
    </script>

    <script>
        function deleteType(id) {
            Swal.fire({
                title: "Вы уверены, что хотите удалить?",
                text: "Это действие нельзя будет отменить!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Да, удалить",
                cancelButtonText: "Отмена"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/res/type/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire("Удалено!", "Тип успешно удален.", "success")
                                .then(() => location.reload());
                        })
                        .catch(error => {
                            Swal.fire("Ошибка", "Ошибка подключения к серверу", "error");
                            console.error(error);
                        });
                }
            });
        }
    </script>

@endsection
