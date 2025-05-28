@extends('layouts.cashier')
@section('content')

    <div class="content-wrapper">
        <!-- Контент -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Unified Table -->
            <div class="card">
                <h5 class="card-header">История продуктов и устройств</h5>
                <form method="GET" action="" class="d-flex m-2" style="max-width: 400px; width: 100%;">
                    <input type="text" name="search" class="form-control mr-2" placeholder="Поиск..." value="{{ request()->search }}">
                    <button type="submit" class="btn btn-primary">Поиск</button>
                </form>
                <div class="table-responsive text-nowrap">
                    <table class="table" style="margin-bottom: 90px">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>Продукт</th>
                            <th>Количество</th>
                            <th>Доход</th>
                            <th>Расход</th>
                            <th>Продано</th>
                            <th>Дата создания</th>
                            <th>Статус</th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        {{-- № для счетчика на странице --}}
                        @php
                            $nm = ($data->currentPage() - 1) * $data->perPage() + 1;
                        @endphp

                        @foreach($data as $history)
                            <tr class="@if(!isset($history->sold)) bg-warning @else bg-success @endif text-white ">
                                <td>{{ $nm++ }}</td>

                                <!-- Product Name (from product history) -->
                                <td>{{ isset($history->product->name) ? $history->product->name : '-' }}</td>

                                <!-- Count (common to both product and device history) -->
                                <td>{{ isset($history->count) ? $history->count : '-' }}</td>

                                <!-- Income (from product history) -->
                                <td>
                                    @if(isset($history->income))
                                        {{ number_format($history->income, 0, '.', ' ') . " сум" }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <!-- Expense (from product history) -->
                                <td>
                                    @if(isset($history->expense))
                                        {{ number_format($history->expense, 0, '.', ' ') . " сум" }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <!-- Sold (from device history) -->
                                <td>
                                    @if(isset($history->sold))
                                        {{ number_format($history->sold, 0, '.', ' ') . " сум" }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <!-- Created At -->
                                <td>
                                    {{ \Carbon\Carbon::parse($history->created_at)->format('Y-m-d H:i') }}
                                </td>

                                <!-- Status Badge -->
                                <td>
                                    @if(isset($history->sold))
                                        <span class="badge bg-label-success me-1">
                                            <i class="bx bx-arrow-from-bottom"></i> <!-- Sold -->
                                        </span>
                                    @else
                                        <span class="badge bg-label-danger me-1">
                                            <i class="bx bx-arrow-to-bottom"></i> <!-- Not sold -->
                                        </span>
                                    @endif
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3 d-flex justify-content-center">
                    {{ $data->links('pagination::bootstrap-4') }}
                </div>
            </div>
            <!-- / Unified Table -->
        </div>
        <!-- / Контент -->
        <div class="content-backdrop fade"></div>
    </div>

@endsection
