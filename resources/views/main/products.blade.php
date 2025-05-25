@extends('layouts.main')
@section('content')


    @include('main.products.create')
    @include('main.products.edit')
    @include('main.products.add-product')

    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Basic Bootstrap Table -->
            <div class="card">
                <h5 class="card-header">Mahsulotlar</h5>
                <div class="text-end">
                    <button class="btn btn-secondary create-new btn-primary waves-effect waves-light" tabindex="0" data-bs-toggle="modal" data-bs-target="#addModal" type="button"><span><i class="ri-add-line"></i> <span class="d-none d-sm-inline-block">Maxsulot yaratish</span></span></button>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table" style="margin-bottom: 90px">
                        <thead class="text-center">
                        <tr>
                            <th>N</th>
                            <th>Mahsulot nomi</th>
                            <th>Sotib olindi <i class="bx bx-arrow-to-bottom text-success"></i></th>
                            <th>Sotiladi <i class="bx bx-arrow-to-top text-danger"></i></th>
                            <th>Mahsulot soni</th>
                            <th>Amallar</th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0 text-center">
                        @foreach($data as $val)
                            <tr>
                                <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>1</strong></td>
                                <td>{{$val->name}}</td>
                                <td><span class="badge bg-label-success me-1">{{$val->income}} so'm</span></td>
                                <td><span class="badge bg-label-danger me-1">{{$val->expense}} so'm</span></td>
                                <td><span class="badge bg-label-primary me-1">{{$val->count}} ta</span></td>
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
            </div>
            <!--/ Basic Bootstrap Table -->

        </div>
        <!-- / Content -->

    <script>
        function deleteProduct(id) {
            Swal.fire({
                title: "O‘chirishni tasdiqlaysizmi?",
                text: "Bu amalni bekor qilib bo‘lmaydi!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ha, o‘chirish",
                confirmButtonColor: "red",
                cancelButtonText: "Bekor qilish"
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
                            Swal.fire("O‘chirildi!", "Tur muvaffaqiyatli o‘chirildi.", "success")
                                .then(() => location.reload());
                        })
                        .catch(error => {
                            Swal.fire("Xatolik", "Server bilan ulanishda muammo.", "error");
                            console.error(error);
                        });
                }
            });
        }
    </script>

@endsection
