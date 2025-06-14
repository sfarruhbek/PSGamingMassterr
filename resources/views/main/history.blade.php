@extends('layouts.main')
@section('content')

    <div class="content-wrapper">
        <!-- Контент -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Основная таблица -->
            <div class="card">
                <h5 class="card-header">История</h5>
                <form method="GET" action="" class="d-flex m-2" style="max-width: 400px; width: 100%;">
                    <input type="text" name="search" class="form-control mr-2" placeholder="Qidiruv..." value="{{ request()->search }}">
                    <button type="submit" class="btn btn-primary">Qidirish</button>
                </form>
                <div class="table-responsive text-nowrap">
                    <table class="table" style="margin-bottom: 90px">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>Номер компьютера</th>
                            <th>Тип</th>
{{--                            <th>Использование</th>--}}
                            <th>Время начала</th>
                            <th>Время использования</th>
                            <th>Оплаченная сумма</th>
                            <th>Действие</th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        {{-- № uchun hisoblagichni sahifaga moslash uchun --}}
                        @php
                            $nm = ($data->currentPage() - 1) * $data->perPage() + 1;
                        @endphp

                        @foreach($data as $val)
                            <tr>
                                <td>{{ $nm++ }}</td>
                                <td>{{ $val->device->name ?? '-' }}</td>
                                <td>
                            <span class="badge bg-label-primary me-1">
                                {{ $val->device->type->name ?? '-' }}
                            </span>
                                </td>
{{--                                <td>{{ $val->use ?? '-' }}</td>--}}
                                <td>{{ $val->started_at ? \Carbon\Carbon::parse($val->started_at)->format('Y-m-d H:i') : '-' }}</td>
                                <td>
                                    @if($val->started_at && $val->finished_at)
                                        @php
                                            $start = \Carbon\Carbon::parse($val->started_at);
                                            $end = \Carbon\Carbon::parse($val->finished_at);
                                            $diff = $start->diff($end);
                                            $used = ($diff->h ? $diff->h . ' ч. ' : '') . $diff->i . ' мин.';
                                        @endphp
                                        {{ $used }}
                                    @else
                                        -
                                    @endif
                                </td>
                                    <?php

                                    if (!function_exists('getTotalSum')) {
                                        function getTotalSum($data)
                                        {
                                            $totalSold = 0;

                                            if (!empty($data->productHistory)) {
                                                foreach ($data->productHistory as $item) {
                                                    $totalSold += $item->sold ?? 0;
                                                }
                                            }
                                            return $totalSold + ($data->paid_price ?? 0);
                                        }
                                    }
                                    ?>
                                <td>{{ getTotalSum($val) ? number_format(getTotalSum($val), 0, '.', ',') . " сум" : '-' }}</td>
                                <td>
                                    <button class="btn btn-info" onclick='showMore({!! json_encode($val) !!})'>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                        </svg>
                                    </button>
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
        <div class="content-backdrop fade"></div>
    </div>


    <script>
        function formatTime(datetimeStr) {
            const date = new Date(datetimeStr.replace(' ', 'T'));
            const month = date.getMonth() + 1;

            const timeString = date.toLocaleTimeString(undefined, {
                hour: '2-digit',
                minute: '2-digit'
            });

            const monthName = date.toLocaleString(undefined, { month: 'long' });

            return `${month}-${monthName} ${timeString}`;
        }
        function showMore(data){
            console.log(data);

            let startTime = formatTime(data.started_at);
            let endTime = formatTime(data.finished_at);
            let duration = getDuration(data.started_at, data.finished_at);
            let deviceTotalSold = `${data.paid_price.toLocaleString()} сум`;

            let total = getTotalSum(data).toLocaleString()  + " сум";

            let productsHtml = '';
            data.product_history.forEach(item => {
                const name = item.product.name;
                const count = item.count;
                const sold = item.sold.toLocaleString('ru-RU');
                productsHtml += `<p>${name} (${count} шт): ${sold} сум</p>`;
            });

            Swal.fire({
                title: 'Device name',
                html: `
                    <div style="text-align: left; font-size: 16px;">
                      <p><strong>Начало:</strong> ${startTime}</p>
                      <p><strong>Окончание:</strong> ${endTime}</p>
                      <p><strong>Продолжительность:</strong> ${duration}</p>
                      <p><strong>Сумма по устройству:</strong> ${deviceTotalSold}</p>
                      <hr>
                      <h4>Товары</h4>
                      ${productsHtml}
                      <hr>
                      <p><strong>💰 Итого оплачено:</strong> ${total}</p>
                    </div>
                  `,
                focusConfirm: false,
                confirmButtonText: 'OK',
            });
        }
        function getDuration(start, end) {
            const startTime = new Date(start.replace(' ', 'T'));
            const endTime = new Date(end.replace(' ', 'T'));
            const diffMs = endTime - startTime;
            const minutes = Math.floor(diffMs / 60000);
            const seconds = Math.floor((diffMs % 60000) / 1000);
            return `${minutes} мин ${seconds} сек`;
        }
        function getTotalSum(data) {
            const totalSold = data.product_history.reduce((sum, item) => sum + item.sold, 0);
            return totalSold + data.paid_price;
        }
    </script>

@endsection
