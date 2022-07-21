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

                    {{ __('You are logged in!') }}
                    <form method="post" action="{{ route('goods.movement', ['action'=>'pull']) }}">
                        @csrf
                        <div>Pull goods</div>
                        <label>Goods
                            <input type="text" name="goods_id">
                        </label>
                        <label>Amount
                            <input type="text" name="amount">
                        </label>
                        <input type="submit" value="confirm">
                    </form>
                        <form method="post" action="{{ route('goods.movement', ['action'=>'push']) }}">
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
                                <input type="text" name="storage_id_from">
                            </label>

                            <input type="submit" value="confirm">
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
