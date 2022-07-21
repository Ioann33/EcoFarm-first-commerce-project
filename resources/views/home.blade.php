@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h1>Тест POST запросов</h1>
                        <form method="post" action="{{ route('set.price') }}">
                            @csrf
                            <div>Set prise</div>
                            <label>movement id
                                <input type="text" name="movement_id">
                            </label>
                            <label>price
                                <input type="text" name="price">
                            </label>

                            <input type="submit" value="confirm">
                        </form>
                    <form method="post" action="{{ route('goods.movement.pull') }}">
                        @csrf
                        <div>Pull goods</div>
                        <label>movement id
                            <input type="text" name="movement_id">
                        </label>

                        <input type="submit" value="confirm">
                    </form>
                        <form method="post" action="{{ route('goods.movement.push') }}">
                            @csrf
                            <div>Push goods</div>
                            <label>Goods
                                <input type="text" name="goods_id">
                            </label>
                            <label>Amount
                                <input type="text" name="amount">
                            </label>
                            <label>From
                                <input type="text" name="storage_id_from">
                            </label>
                            <label>to
                                <input type="text" name="storage_id_to">
                            </label>
                            <label>order_main
                                <input type="text" name="order_main">
                            </label>
                            <label>price
                                <input type="text" name="price">
                            </label>

                            <input type="submit" value="confirm">
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
