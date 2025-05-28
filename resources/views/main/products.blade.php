@extends('layouts.main')
@section('content')

    @include('main.products.create')
    @include('main.products.edit')
    @include('main.products.add-product')

    <div class="content-wrapper">
        <!-- Контент -->
        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Основная таблица -->
            <div class="card">
                <h5 class="card-header">Продукты</h5>
                <form method="GET" action="" class="d-flex m-2" style="max-width: 400px; width: 100%;">
                    <input type="text" name="search" class="form-control mr-2" placeholder="Qidiruv..." value="{{ request()->search }}">
                    <button type="submit" class="btn btn-primary">Qidirish</button>
                </form>
                <div class="text-end">
                    <button class="btn btn-secondary create-new btn-primary waves-effect waves-light" tabindex="0" data-bs-toggle="modal" data-bs-target="#addModal" type="button">
                        <span><i class="ri-add-line"></i> <span class="d-none d-sm-inline-block">Добавить продукт</span></span>
                    </button>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table" style="margin-bottom: 90px">
                        <thead class="text-center">
                        <tr>
                            <th>№</th>
                            <th>Название продукта</th>
                            <th>Закупочная цена <i class="bx bx-arrow-to-bottom text-danger"></i></th>
                            <th>Цена продажи <i class="bx bx-arrow-to-top text-success"></i></th>
                            <th>Количество</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0 text-center">
                        <?php $nm = 1; ?>
                        @foreach($data as $val)
                            <tr>
                                <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{$nm++}}</strong></td>
                                <td>{{$val->name}}</td>
                                <td><span class="badge bg-label-danger me-1">{{$val->income}} сум</span></td>
                                <td><span class="badge bg-label-success me-1">{{$val->expense}} сум</span></td>
                                <td><span class="badge bg-label-primary me-1">{{$val->count}} шт</span></td>
                                <td>
                                    <button class="btn btn-primary" onclick="addProduct({{$val}})"><i class="bx bxs-cart-download"></i></button>
                                    <button class="btn btn-warning" onclick="editProduct({{$val}})"><i class="bx bx-pencil"></i></button>
                                    <button class="btn btn-danger" onclick="deleteProduct({{$val->id}})"><i class="bx bx-trash"></i></button>
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
        <!-- / Контент -->

        <script>
            function deleteProduct(id) {
                Swal.fire({
                    title: "Вы уверены, что хотите удалить?",
                    text: "Это действие нельзя будет отменить!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Да, удалить",
                    confirmButtonColor: "red",
                    cancelButtonText: "Отмена"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/res/product/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                Swal.fire("Удалено!", "Продукт успешно удален.", "success")
                                    .then(() => location.reload());
                            })
                            .catch(error => {
                                Swal.fire("Ошибка", "Проблема с подключением к серверу.", "error");
                                console.error(error);
                            });
                    }
                });
            }
        </script>

@endsection
