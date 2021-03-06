<div class="form-group row{{ $errors->has('type') ? ' has-danger' : '' }}">
    <label class="form-control-label col-md-2">{{ __('Customer type') }}</label>
    <div class="col-md-10">
        @foreach($types as $key => $value)
            <label class="radio-inline" for="type_{{ $key }}">
                {{ Form::radio('type', $key, $customer->type->value() == $key, ['id' => "type_$key", 'v-model' =>
                'customerType']) }}
                {{ $value }}
                &nbsp;
            </label>
        @endforeach

        @if ($errors->has('type'))
            <div class="form-control-feedback">{{ $errors->first('type') }}</div>
        @endif
    </div>
</div>

<hr>

<div class="row">

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('firstname') ? ' has-danger' : '' }}">
            {{ Form::text('firstname', null, [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => __('First name')
                ])
            }}

            @if ($errors->has('firstname'))
                <div class="form-control-feedback">{{ $errors->first('firstname') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('lastname') ? ' has-danger' : '' }}">
            {{ Form::text('lastname', null, [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => __('Last name')
                ])
            }}

            @if ($errors->has('lastname'))
                <div class="form-control-feedback">{{ $errors->first('lastname') }}</div>
            @endif
        </div>
    </div>

</div>

<div id="customer-organization" v-show="customerType == 'organization'">
    @include('appshell::customer._organization')
</div>

@section('scripts')
    <script>
        new Vue({
            el: '#app',
            data: {
                customerType: '{{ old('type') ?: $customer->type->value() }}'
            }
        });
    </script>
@stop
