@extends('layouts.main')
@section('content')

    <div class="content-wrapper">
        <!-- Контент -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Основная таблица -->
            <div class="card">
                <h5 class="card-header">История</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table" style="margin-bottom: 90px">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>Номер компьютера</th>
                            <th>Тип</th>
                            <th>Использование</th>
                            <th>Время начала</th>
                            <th>Время использования</th>
                            <th>Оплаченная сумма</th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        @foreach($data as $val)
                            <tr>
                                <td>{{ $val->id }}</td>
                                <td>{{ $val->device->name ?? '-' }}</td>
                                <td>
                                <span class="badge bg-label-primary me-1">
                                    {{ $val->device->type->name ?? '-' }}
                                </span>
                                </td>
                                <td>{{ $val->use ?? '-' }}</td>
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
            </div>
            <!-- / Основная таблица -->
        </div>
        <!-- / Контент -->
        <div class="content-backdrop fade"></div>
    </div>

@endsection
