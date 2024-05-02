<div class="form-select-guests" style="background-color: #ffffff; border-radius: 7px;padding:0px;">
    <div class="form-group" style="padding-bottom:6px">
        <i class="field-icon icofont-user" style="color:#000033;font-size: 25px;"></i>
        <div class="form-content" data-toggle="dropdown" style="">{{--dropdown-toggle This to add the small arrow(dropdown arrow) in guest input--}}
            <div class="" style="">
                {{-- <label style="color:#1A2B50;font-size:16px;font-weight: 500;"> {{ $field['title'] }} </label> --}}
                @php
                $adults = request()->query('adults',1);
                $children = request()->query('children',0);
                @endphp
                <div class="render" style="font-size:12px;color:#000033;">
                    <span class="adults"><span class="one @if($adults >1) d-none @endif">{{__('1 Adult')}}</span> <span class="@if($adults <= 1) d-none @endif multi" data-html="{{__(':count Adults')}}">{{__(':count Adults',['count'=>request()->query('adults',1)])}}</span></span>
                    <!----->
                    <span class="children  hidden d-none">
                        <span class="one @if($children >1) d-none @endif" data-html="{{__(':count Child')}}">{{__(':count Child',['count'=>request()->query('children',0)])}}</span>
                        <span class="multi @if($children <=1) d-none @endif" data-html="{{__(':count Children')}}">{{__(':count Children',['count'=>request()->query('children',0)])}}</span>
                    </span>
                </div>
            </div>
        </div>
        <div class="dropdown-menu select-guests-dropdown">
            <div class="dropdown-item-row hidden d-none">
                <div class="label">{{__('Rooms')}}</div>
                <div class="val">
                    <span class="btn-minus" data-input="room"><i class="icon ion-md-remove"></i></span>
                    <span class="count-display"><input type="number" name="room" value="{{request()->query('room',1)}}" min="1"></span>
                    <span class="btn-add" data-input="room"><i class="icon ion-ios-add"></i></span>
                </div>
            </div>
            <div class="dropdown-item-row">
                <div class="label">{{__('Adults')}}</div>
                <div class="val">
                    <span class="btn-minus" data-input="adults"><i class="icon ion-md-remove"></i></span>
                    <span class="count-display"><input type="number" name="adults" value="{{request()->query('adults',1)}}" min="1"></span>
                    <span class="btn-add" data-input="adults"><i class="icon ion-ios-add"></i></span>
                </div>
            </div>
            <div class="dropdown-item-row  hidden d-none">
                <div class="label">{{__('Children')}}</div>
                <div class="val">
                    <span class="btn-minus" data-input="children"><i class="icon ion-md-remove"></i></span>
                    <span class="count-display"><input type="number" name="children" value="{{request()->query('children',0)}}" min="0"></span>
                    <span class="btn-add" data-input="children"><i class="icon ion-ios-add"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Edit the search bar and it include three other pages guest - location - date and the main layout form search --}}
