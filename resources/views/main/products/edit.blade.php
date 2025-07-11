<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Редактирование</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Закрыть"
                ></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="name" class="form-label">Название продукта</label><br>
                            <input id="editName" type="text" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-1">
                            <input id="editExpenseCheckBox" type="checkbox">
                            <label class="form-label">Изменить цену продажи (за 1 шт, в сумах)</label>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-3">
                            <input disabled type="number" id="editExpense" name="expense" class="form-control" placeholder="Например: 6000" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(session('update'))
    <script>
        Swal.fire({
            title: "Обновлено!",
            text: "Продукт успешно обновлен",
            icon: "success"
        });
    </script>
@endif

<script>
    let formEditProduct = document.getElementById('editForm');
    function editProduct(product){
        let form_url = "{{ route('res.product.update', 0) }}";
        form_url = form_url.slice(0, -1) + product['id'];

        let name = document.getElementById('editName');
        let expenseCheck = document.getElementById('editExpenseCheckBox');
        let editExpense = document.getElementById('editExpense');

        name.value = product['name'];
        editExpense.value = product['expense'];
        formEditProduct.action = form_url;

        $('#editModal').modal('show');

        expenseCheck.addEventListener('click', function () {
            if (expenseCheck.checked) {
                editExpense.disabled = false;
                editExpense.focus();
            } else {
                editExpense.disabled = true;
            }
        });
    }
</script>
