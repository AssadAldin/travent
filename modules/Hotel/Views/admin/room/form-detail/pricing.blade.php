@if(is_default_lang())
{{--    {{dd($row->full_day)}}--}}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label><input type="checkbox" name="day_stay" @if($row->day_stay) checked @endif value="1"> {{__('Day Stay')}}
                </label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label><input type="checkbox" name="full_day" @if($row->full_day) checked @endif value="1"> {{__('Full Day')}}
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>{{__("Day stay")}}<span class="text-danger">*</span></label>
                <input type="number" required value="{{$row->price}}" min="0" placeholder="{{__("Price")}}" name="price" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{__("Full day")}}<span class="text-danger">*</span></label>
                <input type="number" required value="{{$row->price2}}" min="0" placeholder="{{__("Price2")}}" name="price2" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{__("Weekend Day stay")}}<span class="text-danger">*</span></label>
                <input type="number" required value="{{$row->weekendsPrice}}" min="0" placeholder="{{__("weekendsPrice")}}" name="weekendsPrice" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{__("Weekend Full day")}}<span class="text-danger">*</span></label>
                <input type="number" required value="{{$row->weekendsPrice2}}" min="0" placeholder="{{__("weekendsPrice2")}}" name="weekendsPrice2" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{__("Number of room")}} <span class="text-danger">*</span></label>
                <input type="number" required value="{{$row->number ?? 1}}" min="1" max="100" placeholder="{{__("Number")}}" name="number" class="form-control">
            </div>
        </div>
    </div>
    <hr>
    @if(is_default_lang())
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="control-label">{{__("Minimum day stay requirements")}}</label>
                    <input type="number" name="min_day_stays" class="form-control" value="{{$row->min_day_stays}}" placeholder="{{__("Ex: 2")}}">
                    <i>{{ __("Leave blank if you dont need to set minimum day stay option") }}</i>
                </div>
            </div>
        </div>
        <hr>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>{{__("Number of beds")}} </label>
                <input type="number"  value="{{$row->beds ?? 1}}" min="1" max="10" placeholder="{{__("Number")}}" name="beds" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{__("Room Size")}} </label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="size" value="{{$row->size ?? 0}}" placeholder="{{__("Room size")}}" >
                    <div class="input-group-append">
                        <span class="input-group-text" >{!! size_unit_format() !!}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{__("Max Adults")}} </label>
                <input type="number" min="1"  value="{{$row->adults ?? 1}}"  name="adults" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group hidden d-none">
                <label>{{__("Max Children")}} </label>
                <input type="number" min="0"  value="{{$row->children ?? 0}}"  name="children" class="form-control">
            </div>
        </div>
    </div>
    <hr>
@endif
