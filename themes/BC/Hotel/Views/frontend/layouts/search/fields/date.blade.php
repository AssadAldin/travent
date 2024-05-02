<div class="form-group w-full" style="background-color: #ffffff; border-radius: 7px;">

    <div class="form-content" style="">
        <div class="form-date-search-hotel">
            <div class="date-wrapper">
                <div class="check-in-wrapper d-flex align-items-center">
                    <i class="fa fa-calendar fa-2x mx-2"></i>
                    <div style="font-size:13px;color:#1A2B50;" class="mx-2">{{__('Date')}}</div>
                    <div style="font-size:12px;color:#1A2B50;" class="render check-in-render">{{Request::query('start',display_date(strtotime("today")))}}</div>
                    <span style="font-size:12px;color:#1A2B50;" class="mx-2"> _ </span>
                    <div style="font-size:12px;color:#1A2B50;" class="render check-out-render">{{Request::query('end',display_date(strtotime("+1 day")))}}</div>
                    {{-- <label>{{ $field['title'] }}</label> --}}
                    <!--<div class="render check-in-render" style="font-size:12px;color:#1A2B50;">-->
                    <!--    @if(!Request::query('start',display_date(strtotime("today"))))-->
                    <!--    {{Request::query('start',display_date(strtotime("today")))}}-->
                    <!--    @else-->
                    <!--    {{__('Check In')}}-->
                    <!--    @endif-->
                    <!--</div>-->
                    <!--<span style="font-size:12px;color:#1A2B50;"> - </span>-->
                    <!--<div class="render check-out-render" style="font-size:12px;color:#1A2B50;">-->
                    <!--    @if(!Request::query('end',display_date(strtotime("+1 day"))))-->
                    <!--    {{Request::query('end',display_date(strtotime("+1 day")))}}-->
                    <!--    @else-->
                    <!--    {{__('Check Out')}}-->
                    <!--    @endif-->
                    <!--</div>-->
                </div>
            </div>
            <input type="hidden" class="check-in-input" value="{{Request::query('start',display_date(strtotime("today")))}}" name="start">
            <input type="hidden" class="check-out-input" value="{{Request::query('end',display_date(strtotime("+1 day")))}}" name="end">
            <input type="text" class="check-in-out" value="{{Request::query('date',date("Y-m-d")." - ".date("Y-m-d",strtotime("+1 day")))}}">
        </div>
    </div>
</div>
{{-- Edit the search bar and it include three other pages guest - location - date and the main layout form search --}}
