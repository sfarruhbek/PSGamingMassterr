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
                                <td>{{ $val->paid_price ? number_format($val->paid_price, 0, '.', ' ') . " сум" : '-' }}</td>
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

@endsection
