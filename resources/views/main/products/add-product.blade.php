<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Maxsulot qo'shish</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addProductForm" method="POST" action="{{route('product.add-product')}}">
                    @csrf
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="name" class="form-label">Mahsulot nomi</label><br>
                            <input type="text" id="addProductName" disabled class="form-control">
                            <input type="text" id="addProductId" hidden name="product_id" required>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label id="addProductCountLabel" for="count" class="form-label">Soni</label>
                            <input type="number" name="count" class="form-control" placeholder="Masalan: 100" min="1" required>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="income" class="form-label">Kirim narxi (sotib olinadigan narx, 1 tasi, so'mda)</label>
                            <input disabled type="number" id="addProductIncome" name="income" class="form-control" placeholder="Masalan: 4500" step="0.01" min="0" required>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="expense" class="form-label">Chiqim narxi (sotiladigan narx, 1 tasi, so'mda)</label>
                            <input disabled type="number" id="addProductExpense" name="expense" class="form-control" placeholder="Masalan: 6000" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-3">
                            <input type="checkbox" id="addProductCheckBoxIncomeExpense"> Kirim va Chiqim summasi o'zgartirish
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Yopish</button>
                        <button type="submit" class="btn btn-primary">Saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@if(session('add_product'))
    <script>
        Swal.fire({
            title: "Yangilandi!",
            text: "Mahsulot soni oshirildi",
            icon: "success"
        });
    </script>
@endif
<script>
    let formAddProduct = document.getElementById('addProductForm');

    function addProduct(product) {
        let id = document.getElementById('addProductId');
        let name = document.getElementById('addProductName');
        let countLabel = document.getElementById('addProductCountLabel');
        let checkboxIncomeExpense = document.getElementById('addProductCheckBoxIncomeExpense');
        let income = document.getElementById('addProductIncome');
        let expense = document.getElementById('addProductExpense');

        let modal = new bootstrap.Modal(document.getElementById('addProductModal'));

        id.value = product['id'];
        name.value = product['name'];
        countLabel.innerHTML="Soni(dona)";
        income.value = product['income'];
        expense.value = product['expense'];

        checkboxIncomeExpense.addEventListener('click', function () {
            if (checkboxIncomeExpense.checked) {
                income.disabled = false;
                expense.disabled = false;
                income.focus();
            } else {
                income.disabled = true;
                expense.disabled = true;
            }
        });

        modal.show();
    }
</script>
