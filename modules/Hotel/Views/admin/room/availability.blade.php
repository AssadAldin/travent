<meta name="viewport" content="width=1024">
@extends('admin.layouts.app')

@section('content')
    @php $services  = []; @endphp
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{ __('Room Availability Calendar') }}</h1>
        </div>
        @include('admin.message')
        <div class="panel">
            <div class="panel-body">
                <div class="filter-div d-flex justify-content-between ">
                    <div class="col-right">
                        @if ($rows->total() > 0)
                            <span
                                class="count-string">{{ __('Showing :from - :to of :total rooms', ['from' => $rows->firstItem(), 'to' => $rows->lastItem(), 'total' => $rows->total()]) }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if (count($rows))
            <div class="panel">
                <div class="panel-title"><strong>{{ __('Availability') }} </strong>
                </div>
                <div class="panel-title"><strong><span class="p-1 bg-primary text-white">{{ __('Day Stay') }}</span> - <span
                            class="p-1 bg-success text-white">{{ __('Full Day') }}</span></strong>
                </div>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <div class="nav nav-tabs overflow-auto vertical-nav" id="items_tab" role="tablist">
                            @foreach ($rows as $k => $item)
                                @php $translation = $item->translate(); @endphp
                                <p class="nav-item event-name">
                                    <a class="nav-link" data-id="{{ $item->id }}" data-toggle="tab"
                                        href="#calendar-{{ $item->id }}"
                                        title="{{ $item->title }}">{{ $translation->title }}</a>
                                </p>
                            @endforeach
                        </div>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="col-md-12" style="background: white;padding: 15px;">
                        <div id="dates-calendar" class="dates-calendar w-100"></div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning">{{ __('No rooms found') }}</div>
        @endif
        <div class="d-flex justify-content-center">
            {{ $rows->appends($request->query())->links() }}
        </div>
    </div>
    <div id="bravo_modal_calendar" class="modal fade">
        <div class="modal-dialog modal-lg  modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Date Information') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="row form_modal_calendar form-horizontal" novalidate onsubmit="return false">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Date Ranges') }}</label>
                                <input readonly type="text" class="form-control has-daterangepicker">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Status') }}</label>
                                <br>
                                <label><input true-value=1 false-value=0 type="checkbox" v-model="form.active">
                                    {{ __('Available for booking?') }}</label>
                            </div>
                        </div>
                        {{-- @dd($item->price) --}}
                        <div class="col-md-6" v-show="form.active">
                            <div class="form-group">
                                <label class="mb-0">{{ __('Day Stay admin') }}</label>
                                <!--<small>{{ __('(Sunday - Monday - Tuesday - Wednesday)') }}</small>-->
                                <input type="number" v-model="form.price" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6" v-show="form.active">
                            <div class="form-group">
                                <label class="mb-0">{{ __('Full Day admin') }}</label>
                                <!--<small>{{ __('(Sunday - Monday - Tuesday - Wednesday)') }}</small>-->
                                <input type="number" v-model="form.price2" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6" v-show="form.active">
                            <div class="form-group">
                                <label class="mb-0">{{ __('Weekends Day Stay') }}</label>
                                <div class="">
                                    <!--<small>{{ __('This price will effect only in weekend (Friday - Saturday)') }}</small>-->
                                </div>
                                <input type="number" v-model="form.weekendsPrice" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6" v-show="form.active">
                            <div class="form-group">
                                <label class="mb-0">{{ __('Weekends Full Day') }}</label>
                                <div class="">
                                    <!--<small>{{ __('This price will effect only in weekend (Friday - Saturday)') }}</small>-->
                                </div>
                                <input type="number" v-model="form.weekendsPrice2" class="form-control">
                            </div>
                        </div>

                        {{-- <div class="col-md-6" v-show="form.active">
                            <div class="form-group">
                                <label>{{ __('Number of room') }}</label>
                                <input type="number" v-model="form.number" class="form-control">
                            </div>
                        </div> --}}
                        <div class="col-md-6 d-none" v-show="form.active">
                            <div class="form-group">
                                <label>{{ __('Instant Booking?') }}</label>
                                <br>
                                <label><input true-value=1 false-value=0 type="checkbox" v-model="form.is_instant">
                                    {{ __('Enable instant booking') }}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-0">{{ __('Host notes') }}</label>
                                <div class=""><small>{{ __('Here you can add any note to this day') }}</small></div>
                                <textarea v-model="form.note" class="form-control"></textarea>
                            </div>
                        </div>
                    </form>
                    <div v-if="lastResponse.message">
                        <br>
                        <div class="alert" :class="!lastResponse.status ? 'alert-danger' : 'alert-success'">
                            @{{ lastResponse.message }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary"
                        @click="saveForm({{ $item }})">{{ __('Save changes') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('libs/fullcalendar-4.2.0/core/main.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/fullcalendar-4.2.0/daygrid/main.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/daterange/daterangepicker.css') }}">

    <style>
        .event-name {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        #dates-calendar .loading {}
    </style>
@endpush

@push('js')
    <script src="{{ asset('libs/daterange/moment.min.js') }}"></script>
    <script src="{{ asset('libs/daterange/daterangepicker.min.js?_ver=' . config('app.asset_version')) }}"></script>
    <script src="{{ asset('libs/fullcalendar-4.2.0/core/main.js') }}"></script>
    <script src="{{ asset('libs/fullcalendar-4.2.0/interaction/main.js') }}"></script>
    <script src="{{ asset('libs/fullcalendar-4.2.0/daygrid/main.js') }}"></script>

    <script>
        $('input[name=type1]').change(function() {

            $("#form2").submit();

        });

        var calendarEl, calendar, lastId, formModal;

        $('#items_tab').on('show.bs.tab', function(e) {
            calendarEl = document.getElementById('dates-calendar');
            lastId = $(e.target).data('id');
            if (calendar) {
                calendar.destroy();
            }
            calendar = new FullCalendar.Calendar(calendarEl, {
                buttonText: {
                    today: '{{ __('Today') }}',
                },
                plugins: ['dayGrid', 'interaction'],
                header: {},
                selectable: true,
                selectMirror: false,
                allDay: false,
                editable: false,
                eventLimit: true,
                defaultView: 'dayGridMonth',
                firstDay: daterangepickerLocale.first_day_of_week,
                events: {
                    url: "{{ route('hotel.admin.room.availability.loadDates', ['hotel_id' => $hotel->id]) }}",
                    extraParams: {
                        id: lastId,
                    }
                },
                loading: function(isLoading) {
                    if (!isLoading) {
                        $(calendarEl).removeClass('loading');
                    } else {
                        $(calendarEl).addClass('loading');
                    }
                },
                select: function(arg) {
                    formModal.show({
                        start_date: moment(arg.start).format('YYYY-MM-DD'),
                        end_date: moment(arg.end).format('YYYY-MM-DD'),
                    });
                },
                eventClick: function(info) {
                    var form = Object.assign({}, info.event.extendedProps);
                    form.start_date = moment(info.event.start).format('YYYY-MM-DD');
                    form.end_date = moment(info.event.start).format('YYYY-MM-DD');
                    formModal.show(form);
                },
                eventRender: function(info) {
                    $(info.el).find('.fc-title').html(info.event.extendedProps.title);
                    $(info.el).find('.fc-title2').html(info.event.extendedProps.title2);
                    // console.log(info);
                }
            });
            calendar.render();
        });

        $('.event-name:first-child a').trigger('click');

        formModal = new Vue({
            el: '#bravo_modal_calendar',
            data: {
                lastResponse: {
                    status: null,
                    message: ''
                },
                form: {
                    id: '',
                    note: '',
                    price: '',
                    price2: '',
                    weekendsPrice: '',
                    weekendsPrice2: '',
                    start_date: '',
                    end_date: '',
                    is_instant: '',
                    enable_person: 0,
                    min_guests: 0,
                    max_guests: 0,
                    active: 0,
                    number: 1
                },
                formDefault: {
                    id: '',
                    note: '',
                    price: '',
                    price2: '',
                    weekendsPrice: '',
                    weekendsPrice2: '',
                    start_date: '',
                    end_date: '',
                    is_instant: '',
                    enable_person: 0,
                    min_guests: 0,
                    max_guests: 0,
                    active: 0,
                    number: 1
                },
                person_types: [],
                person_type_item: {
                    name: '',
                    desc: '',
                    min: '',
                    max: '',
                    note: '',
                    price: '',
                    price2: '',
                    weekendsPrice: '',
                    weekendsPrice2: '',
                },
                onSubmit: false
            },
            methods: {
                show: function(form) {
                    $(this.$el).modal('show');
                    this.lastResponse.message = '';
                    this.onSubmit = false;

                    if (typeof form != 'undefined') {
                        this.form = Object.assign({}, form);
                        if (typeof this.form.person_types == 'object') {
                            this.person_types = Object.assign({}, this.form.person_types);
                        }

                        if (form.start_date) {
                            var drp = $('.has-daterangepicker').data('daterangepicker');
                            drp.setStartDate(moment(form.start_date).format(bookingCore.date_format));
                            drp.setEndDate(moment(form.end_date).format(bookingCore.date_format));
                        }
                    }
                },
                hide: function() {
                    $(this.$el).modal('hide');
                    this.form = Object.assign({}, this.formDefault);
                    this.person_types = [];
                },
                saveForm: function(item) {
                    this.form.target_id = lastId;
                    var me = this;
                    me.lastResponse.message = '';
                    if (this.onSubmit) return;

                    if (!this.validateForm()) return;
                    // Get weekends in single
                    // var parts =me.form.start_date.split('-');
                    // var mydate = new Date(parts[0], parts[1] - 1, parts[2]);
                    // if (mydate.getDay() == 6 || mydate.getDay() == 5){
                    //     console.log("weekends ");
                    //     this.form.price = item.weekendsPrice;
                    //     this.form.price2 = item.weekendsPrice2;
                    // }
                    if (this.form.price == "") me.form.price = 0;
                    if (this.form.price2 == "") me.form.price2 = 0;
                    if (this.form.weekendsPrice == "") me.form.weekendsPrice = 0;
                    if (this.form.weekendsPrice2 == "") me.form.weekendsPrice2 = 0;
                    // console.log(me.form);
                    this.onSubmit = true;
                    this.form.person_types = Object.assign({}, this.person_types);
                    $.ajax({
                        url: '{{ route('hotel.admin.room.availability.store', ['hotel_id' => $hotel->id]) }}',
                        data: this.form,
                        dataType: 'json',
                        method: 'post',
                        success: function(json) {
                            if (json.status) {
                                if (calendar)
                                    calendar.refetchEvents();
                                me.hide();
                            }
                            me.lastResponse = json;
                            me.onSubmit = false;
                        },
                        error: function(e) {
                            me.onSubmit = false;
                        }
                    });
                },
                validateForm: function() {
                    if (!this.form.start_date) return false;
                    if (!this.form.end_date) return false;
                    // if (!this.form.price) return false;
                    // if (!this.form.price2) return false;
                    return true;
                },
                addItem: function() {
                    // console.log(this.person_types);
                    this.person_types.push(Object.assign({}, this.person_type_item));
                },
                deleteItem: function(index) {
                    this.person_types.splice(index, 1);
                }
            },
            created: function() {
                var me = this;
                this.$nextTick(function() {
                    $('.has-daterangepicker').daterangepicker({
                            "locale": {
                                "format": bookingCore.date_format
                            }
                        })
                        .on('apply.daterangepicker', function(e, picker) {
                            // console.log(picker);
                            me.form.start_date = picker.startDate.format('YYYY-MM-DD');
                            me.form.end_date = picker.endDate.format('YYYY-MM-DD');
                            // console.log(picker);
                        });

                    $(me.$el).on('hide.bs.modal', function() {

                        this.form = Object.assign({}, this.formDefault);
                        this.person_types = [];

                    });

                })
            },
            mounted: function() {
                // $(this.$el).modal();
            }
        });
    </script>
@endpush
