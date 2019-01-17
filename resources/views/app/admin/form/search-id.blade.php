<div class="{{$viewClass['form-group']}} {!! !$errors->has($column) ?: 'has-error' !!}">
    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')
        <div class="input-group">
            <input {!! $attributes !!}  />
            <div class="input-group-btn">
                <div class="btn btn-primary ">
                    <i class="fa fa-long-arrow-up"></i>&nbsp;
                    <span class="hidden-xs">选择</span>
                </div>
            </div>
        </div>
        @include('admin::form.help-block')
    </div>
</div>