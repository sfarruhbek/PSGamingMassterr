@extends('layouts.main')
@section('content')


    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Basic Bootstrap Table -->
            <div class="card">
                <h5 class="card-header">Turlar</h5>
                <div class="text-end"><button onclick="addType()" class="btn btn-primary m-2">Add</button></div>
                <div class="table-responsive text-nowrap">
                    <table class="table" style="margin-bottom: 90px">
                        <thead>
                        <tr>
                            <th>N</th>
                            <th>Nomi</th>
                            <th>Narxi Yengil</th>
                            <th>Narxi O'gir</th>
                            <th>Soni</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        @foreach($data as $val)
                        <tr>
                            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{$val->id}}</strong></td>
                            <td>{{$val->name}}</td>
                            <td>
                                2: {{$val->price11}} so'm<br>
                                4: {{$val->price21}} so'm
                            </td>
                            <td>
                                2: {{$val->price12}} so'm<br>
                                4: {{$val->price22}} so'm
                            </td>
                            <td>{{count($val->devices)}}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a onclick="editType({{$val}})" class="dropdown-item" href="javascript:void(0);"
                                        ><i class="bx bx-edit-alt me-1"></i> Edit</a
                                        >
                                        <a onclick="deleteType({{$val->id}})" class="dropdown-item text-danger" href="javascript:void(0);"
                                        ><i class="bx bx-trash me-1"></i> Delete</a
                                        >
                                    </div>
                                </div>
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

        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->

    <script>
        function addType() {
            Swal.fire({
                title: "Tur qo'shish",
                html: `
            <div style="text-align: left;">
                <div style="margin-bottom: 15px;">
                    <label for="name">Nomi</label><br>
                    <input id="name" type="text" placeholder="Tur nomi" style="width: 100%; padding: 8px; margin-top: 5px;">
                </div>

                <div style="margin-bottom: 10px;">
                    <strong>2 pultgacha narxlar:</strong>
                    <div style="display: flex; gap: 10px; margin-top: 5px;">
                        <div style="flex: 1;">
                            <label for="price11">Yengil</label><br>
                            <input id="price11" type="number" placeholder="Narxni kiriting..." style="width: 100%; padding: 8px; margin-top: 5px;">
                        </div>
                        <div style="flex: 1;">
                            <label for="price12">Og'ir</label><br>
                            <input id="price12" type="number" placeholder="Narxni kiriting..." style="width: 100%; padding: 8px; margin-top: 5px;">
                        </div>
                    </div>
                </div>

                <div>
                    <strong>2 dan ortiq pult uchun narxlar:</strong>
                    <div style="display: flex; gap: 10px; margin-top: 5px;">
                        <div style="flex: 1;">
                            <label for="price21">Yengil</label><br>
                            <input id="price21" type="number" placeholder="Narxni kiriting..." style="width: 100%; padding: 8px; margin-top: 5px;">
                        </div>
                        <div style="flex: 1;">
                            <label for="price22">Og'ir</label><br>
                            <input id="price22" type="number" placeholder="Narxni kiriting..." style="width: 100%; padding: 8px; margin-top: 5px;">
                        </div>
                    </div>
                </div>
            </div>
        `,
                showCancelButton: true,
                confirmButtonText: "Saqlash",
                cancelButtonText: "Bekor qilish",
                preConfirm: () => {
                    const name = document.getElementById("name").value.trim();
                    const price11 = document.getElementById("price11").value;
                    const price12 = document.getElementById("price12").value;
                    const price21 = document.getElementById("price21").value;
                    const price22 = document.getElementById("price22").value;

                    if (!name || !price11 || !price12 || !price21 || !price22) {
                        Swal.showValidationMessage("Barcha maydonlarni to‘ldiring");
                        return false;
                    }

                    return { name, price11, price12, price21, price22 };
                }
            }).then(result => {
                if (result.isConfirmed) {
                    console.log("Jo‘natilayotgan ma’lumot:", result.value);

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
                                    title: "Saqlandi!",
                                    text: "Yangi tur qo‘shildi.",
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(result => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            })
                            .catch(error => {
                                Swal.fire("Xatolik", "Serverga ulanishda xatolik", "error");
                                console.error(error);
                            });

                    }
                }
            });
        }


    </script>


    <script>
        function editType(type) {
            Swal.fire({
                title: "Turni tahrirlash",
                html: `
            <div style="text-align: left;">
                <div style="margin-bottom: 15px;">
                    <label for="name">Nomi</label><br>
                    <input id="name" type="text" value="${type.name}" placeholder="Tur nomi" style="width: 100%; padding: 8px;">
                </div>

                <div style="margin-bottom: 10px;">
                    <strong>1-qator narxlari:</strong>
                    <div style="display: flex; gap: 10px;">
                        <div style="flex: 1;">
                            <label for="price11">Narx 11</label><br>
                            <input id="price11" type="number" value="${type.price11}" placeholder="Price 11" style="width: 100%; padding: 8px;">
                        </div>
                        <div style="flex: 1;">
                            <label for="price12">Narx 12</label><br>
                            <input id="price12" type="number" value="${type.price12}" placeholder="Price 12" style="width: 100%; padding: 8px;">
                        </div>
                    </div>
                </div>

                <div>
                    <strong>2-qator narxlari:</strong>
                    <div style="display: flex; gap: 10px;">
                        <div style="flex: 1;">
                            <label for="price21">Narx 21</label><br>
                            <input id="price21" type="number" value="${type.price21}" placeholder="Price 21" style="width: 100%; padding: 8px;">
                        </div>
                        <div style="flex: 1;">
                            <label for="price22">Narx 22</label><br>
                            <input id="price22" type="number" value="${type.price22}" placeholder="Price 22" style="width: 100%; padding: 8px;">
                        </div>
                    </div>
                </div>
            </div>
        `,
                showCancelButton: true,
                confirmButtonText: "Yangilash",
                cancelButtonText: "Bekor qilish",
                preConfirm: () => {
                    const updated = {
                        name: document.getElementById("name").value.trim(),
                        price11: document.getElementById("price11").value,
                        price12: document.getElementById("price12").value,
                        price21: document.getElementById("price21").value,
                        price22: document.getElementById("price22").value,
                    };

                    if (!updated.name || !updated.price11 || !updated.price12 || !updated.price21 || !updated.price22) {
                        Swal.showValidationMessage("Barcha maydonlarni to‘ldiring");
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
                            Swal.fire("Yangilandi!", "Tur ma’lumotlari o‘zgartirildi.", "success")
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

    <script>
        function deleteType(id) {
            Swal.fire({
                title: "O‘chirishni tasdiqlaysizmi?",
                text: "Bu amalni bekor qilib bo‘lmaydi!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ha, o‘chirish",
                cancelButtonText: "Bekor qilish"
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
