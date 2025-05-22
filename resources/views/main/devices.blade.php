@extends('layouts.main')
@section('content')

    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Basic Bootstrap Table -->
            <div class="card">
                <h5 class="card-header">Kompyuterlar</h5>
                <div class="text-end"><button onclick="addDevice()" class="btn btn-primary m-2">Add</button></div>
                <div class="table-responsive text-nowrap">
                    <table class="table" style="margin-bottom: 90px">
                        <thead>
                        <tr>
                            <th>N</th>
                            <th>Kompyuter raqami</th>
                            <th>Turi</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        @foreach($data as $val)
                            <tr>
                                <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{$val->id}}</strong></td>
                                <td>{{$val->name}}</td>
                                <td><span class="badge bg-label-primary me-1">{{$val->type->name}}</span></td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a onclick="editDevice({{$val}})" class="dropdown-item" href="javascript:void(0);"
                                            ><i class="bx bx-edit-alt me-1"></i> Edit</a
                                            >
                                            <a onclick="deleteDevice({{$val->id}})" class="dropdown-item text-danger" href="javascript:void(0);"
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

    <script>
        const types = @json($types);
        function addDevice() {
            let options = types.map(type => `<option value="${type.id}">${type.name}</option>`).join('');

            Swal.fire({
                title: "Qurilma qo‘shish",
                html: `
            <div style="text-align: left;">
                <div style="margin-bottom: 15px; text-align: center">
                    <label for="deviceName">Qurilma nomi</label><br>
                    <input id="deviceName" type="text" class="swal2-input" style="width: 80%" placeholder="Qurilma nomi">
                </div>

                <div style="margin-bottom: 15px; text-align: center">
                    <label for="deviceType">Qurilma turi</label><br>
                    <select id="deviceType" class="swal2-input" style="width: 80%">
                        ${options}
                    </select>
                </div>
            </div>
        `,
                showCancelButton: true,
                confirmButtonText: "Saqlash",
                cancelButtonText: "Bekor qilish",
                preConfirm: () => {
                    const name = document.getElementById("deviceName").value.trim();
                    const type_id = document.getElementById("deviceType").value;

                    if (!name || !type_id) {
                        Swal.showValidationMessage("Barcha maydonlarni to‘ldiring");
                        return false;
                    }

                    return { name, type_id };
                }
            }).then(result => {
                if (result.isConfirmed) {
                    fetch("/res/device", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify(result.value)
                    })
                        .then(res => res.json())
                        .then(data => {
                            Swal.fire("Qo‘shildi!", "Qurilma muvaffaqiyatli saqlandi.", "success")
                                .then(() => location.reload());
                        })
                        .catch(error => {
                            Swal.fire("Xatolik", "Server bilan ulanishda muammo.", "error");
                            console.error(error);
                        });
                }
            });
        }

        function editDevice(device) {
            let options = types.map(type => {
                return `<option value="${type.id}" ${device.type_id === type.id ? "selected" : ""}>${type.name}</option>`;
            }).join('');

            Swal.fire({
                title: "Qurilmani tahrirlash",
                html: `
            <div style="text-align: left;">
                <div style="margin-bottom: 15px; text-align: center">
                    <label for="deviceName">Qurilma nomi</label><br>
                    <input id="deviceName" type="text" class="swal2-input" style="width: 80%"  value="${device.name}">
                </div>

                <div style="margin-bottom: 15px; text-align: center">
                    <label for="deviceType">Qurilma turi</label><br>
                    <select id="deviceType" class="swal2-input" style="width: 80%">
                        ${options}
                    </select>
                </div>
            </div>
        `,
                showCancelButton: true,
                confirmButtonText: "Saqlash",
                cancelButtonText: "Bekor qilish",
                preConfirm: () => {
                    const name = document.getElementById("deviceName").value.trim();
                    const type_id = document.getElementById("deviceType").value;

                    if (!name || !type_id) {
                        Swal.showValidationMessage("Barcha maydonlarni to‘ldiring");
                        return false;
                    }

                    return { name, type_id };
                }
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/res/device/${device.id}`, {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify(result.value)
                    })
                        .then(res => res.json())
                        .then(data => {
                            Swal.fire("Yangilandi!", "Qurilma saqlandi.", "success")
                                .then(() => location.reload());
                        })
                        .catch(error => {
                            Swal.fire("Xatolik", "Server bilan ulanishda muammo.", "error");
                            console.error(error);
                        });
                }
            });
        }

        function deleteDevice(id) {
            Swal.fire({
                title: "Qurilmani o‘chirishni istaysizmi?",
                text: "Bu amalni bekor qilib bo‘lmaydi!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ha, o‘chirish",
                cancelButtonText: "Bekor qilish"
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/res/device/${id}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            Swal.fire("O‘chirildi!", "Qurilma o‘chirildi.", "success")
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
