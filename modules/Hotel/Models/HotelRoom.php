<?php

namespace Modules\Hotel\Models;

use ICal\ICal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Modules\Booking\Models\Bookable;
use Modules\Booking\Models\Booking;
use Modules\Core\Models\SEO;
use Modules\Media\Helpers\FileHelper;
use Modules\Review\Models\Review;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Hotel\Models\HotelTranslation;
use Modules\User\Models\UserWishList;

class HotelRoom extends Bookable
{
    use SoftDeletes;
    protected $table = 'bravo_hotel_rooms';
    public $type = 'hotel_room';
    public $availabilityClass = HotelRoomDate::class;
    protected $translation_class = HotelRoomTranslation::class;

    protected $fillable = [
        'title',
        'price2',
        'weekendPrice',
        'weekendPrice2',
        'content',
        'status',
        'type1',
        'full_weekend',
        'weekends_days'
    ];

    protected $seo_type = 'hotel_room';
    protected $bookingClass;
    protected $roomDateClass;
    protected $hotelRoomTermClass;
    protected $roomBookingClass;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->bookingClass = Booking::class;
        $this->roomDateClass = HotelRoomDate::class;
        $this->hotelRoomTermClass = HotelRoomTerm::class;
        $this->roomBookingClass = HotelRoomBooking::class;
    }

    public static function getModelName()
    {
        return __("Hotel Room");
    }

    public static function getTableName()
    {
        return with(new static)->table;
    }


    public function terms()
    {
        return $this->hasMany($this->hotelRoomTermClass, "target_id");
    }

    public function isAvailableAt($filters = [])
    {
        if (empty ($filters['start_date']) or empty ($filters['end_date']))
            return true;
        $filters['end_date'] = date("Y-m-d", strtotime($filters['end_date'] . " -1day"));
        $roomDates = $this->getDatesInRange($filters['start_date'], $filters['end_date']);
        $allDates = [];
        $tmp_price = 0;
        $tmp_price2 = 0;
        $tmp_night = 0;
        $hotel = Hotel::find($this->parent_id);
        $period = periodDate($filters['start_date'], $filters['end_date'], true);
        foreach ($period as $dt) {
            // $price = $this->price;
            // $price2 = $this->price2;
            $day_index = date("w", strtotime($dt->format('Y-m-d H:i:s')));
            // dd($day_index);
            if (str_contains($hotel->weekends_days, $day_index)) {
                $price = $this->weekendsPrice;
                $price2 = $this->weekendsPrice2;
            } else {
                $price = $this->price;
                $price2 = $this->price2;
            }
            $allDates[$dt->format('Y-m-d')] = [
                'number' => $this->number,
                'price' => $price,
                'price2' => $price2
            ];
            $tmp_night++;
        }
        if (!empty ($roomDates)) {
            foreach ($roomDates as $row) {
                if (!$row->active or !$row->number or !$row->price or !$row->price2)
                    return false;

                if (!array_key_exists(date('Y-m-d', strtotime($row->start_date)), $allDates))
                    continue;
                // dd($row->start_date);
                $day_index = date("w", strtotime($row->start_date));
                if (str_contains($hotel->weekends_days, $day_index)) {
                    $price = $row->weekendsPrice;
                    $price2 = $row->weekendsPrice2;
                } else {
                    $price = $row->price;
                    $price2 = $row->price2;
                }

                $allDates[date('Y-m-d', strtotime($row->start_date))] = [
                    'number' => $row->number,
                    'price' => $price,
                    'price2' => $price2,
                ];
            }
        }

        $roomBookings = $this->getBookingsInRange($filters['start_date'], $filters['end_date']);
        if (!empty ($roomBookings)) {
            foreach ($roomBookings as $roomBooking) {
                $period = periodDate($roomBooking->start_date, $roomBooking->end_date, false);
                foreach ($period as $dt) {
                    $date = $dt->format('Y-m-d');
                    if (!array_key_exists($date, $allDates))
                        continue;
                    $allDates[$date]['number'] -= $roomBooking->number;
                    if ($allDates[$date]['number'] <= 0) {
                        return false;
                    }
                }
            }
        }

        if (!empty ($this->ical_import_url)) {
            $startDate = $filters['start_date'];
            $endDate = $filters['end_date'];
            $timezone = setting_item('site_timezone', config('app.timezone'));

            try {
                $icalevents = new Ical($this->ical_import_url, [
                    'defaultTimeZone' => $timezone
                ]); #All ICAL companeis details Assadaldin dd($icalevents);
                // dd($endDate);
                // end day come from the filters come with -1 day so I add one day to it
                $endDate = new \DateTime($endDate . ' +1 day');
                $eventRange = $icalevents->eventsFromRange($startDate, $endDate->format('Y/m/d'));
                if (!empty ($eventRange)) {
                    foreach ($eventRange as $item => $value) {
                        if (!empty ($date = $value->dtstart_array[2])) {
                            $allDates[date('Y-m-d', $date)]['number'] -= 1;
                            if ($allDates[date('Y-m-d', $date)]['number'] <= 0) {
                                return false;
                            }
                        }
                    }
                }
            } catch (\Exception $exception) {
                return $this->sendError($exception->getMessage());
            }
        }

        if (!empty ($this->ical_import_url_2)) {
            $startDate = $filters['start_date'];
            $endDate = $filters['end_date'];
            $timezone = setting_item('site_timezone', config('app.timezone'));

            try {
                $icalevents = new Ical($this->ical_import_url_2, [
                    'defaultTimeZone' => $timezone
                ]);
                $endDate = new \DateTime($endDate . ' +1 day');
                $eventRange = $icalevents->eventsFromRange($startDate, $endDate->format('Y/m/d'));
                if (!empty ($eventRange)) {
                    foreach ($eventRange as $item => $value) {
                        if (!empty ($date = $value->dtstart_array[2])) {
                            $allDates[date('Y-m-d', $date)]['number'] -= 1;
                            if ($allDates[date('Y-m-d', $date)]['number'] <= 0) {
                                return false;
                            }
                        }
                    }
                }
            } catch (\Exception $exception) {
                return $this->sendError($exception->getMessage());
            }
        }

        if (!empty ($this->ical_import_url_3)) {
            $startDate = $filters['start_date'];
            $endDate = $filters['end_date'];
            $timezone = setting_item('site_timezone', config('app.timezone'));

            try {
                $icalevents = new Ical($this->ical_import_url_3, [
                    'defaultTimeZone' => $timezone
                ]);
                $endDate = new \DateTime($endDate . ' +1 day');
                $eventRange = $icalevents->eventsFromRange($startDate, $endDate->format('Y/m/d'));
                if (!empty ($eventRange)) {
                    foreach ($eventRange as $item => $value) {
                        if (!empty ($date = $value->dtstart_array[2])) {
                            $allDates[date('Y-m-d', $date)]['number'] -= 1;
                            if ($allDates[date('Y-m-d', $date)]['number'] <= 0) {
                                return false;
                            }
                        }

                    }
                }
            } catch (\Exception $exception) {
                return $this->sendError($exception->getMessage());
            }

        }

        if (!empty ($this->ical_import_url_4)) {
            $startDate = $filters['start_date'];
            $endDate = $filters['end_date'];
            $timezone = setting_item('site_timezone', config('app.timezone'));

            try {
                $icalevents = new Ical($this->ical_import_url_4, [
                    'defaultTimeZone' => $timezone
                ]);
                $endDate = new \DateTime($endDate . ' +1 day');
                $eventRange = $icalevents->eventsFromRange($startDate, $endDate->format('Y/m/d'));
                if (!empty ($eventRange)) {
                    foreach ($eventRange as $item => $value) {
                        if (!empty ($date = $value->dtstart_array[2])) {
                            $allDates[date('Y-m-d', $date)]['number'] -= 1;
                            if ($allDates[date('Y-m-d', $date)]['number'] <= 0) {
                                return false;
                            }
                        }

                    }
                }
            } catch (\Exception $exception) {
                return $this->sendError($exception->getMessage());
            }

        }

        $this->tmp_number = !empty ($allDates) ? (int) min(array_column($allDates, 'number')) : 0;
        if (empty ($this->tmp_number))
            return false;

        //Adult - Children
        if (!empty ($filters['adults']) and $this->adults * $this->tmp_number < $filters['adults']) {
            return false;
        }
        if (!empty ($filters['children']) and $this->children * $this->tmp_number < $filters['children']) {
            return false;
        }

        $this->tmp_price = array_sum(array_column($allDates, 'price'));
        $this->tmp_price2 = array_sum(array_column($allDates, 'price2'));
        $this->tmp_dates = $allDates;
        $this->tmp_nights = $tmp_night;
        //        dd($allDates);
        return true;
    }

    public function isAvailableAtNoIcal($filters = [])
    {
        if (empty ($filters['start_date']) or empty ($filters['end_date']))
            return true;
        $filters['end_date'] = date("Y-m-d", strtotime($filters['end_date'] . " -1day"));
        $roomDates = $this->getDatesInRange($filters['start_date'], $filters['end_date']);
        $allDates = [];
        $tmp_price = 0;
        $tmp_price2 = 0;
        $tmp_night = 0;
        $period = periodDate($filters['start_date'], $filters['end_date'], true);
        foreach ($period as $dt) {
            $allDates[$dt->format('Y-m-d')] = [
                'number' => $this->number,
                'price' => $this->price,
                'price2' => $this->price2
            ];
            $tmp_night++;
        }
        if (!empty ($roomDates)) {
            foreach ($roomDates as $row) {
                if (!$row->active or !$row->number or !$row->price or !$row->price2)
                    return false;

                if (!array_key_exists(date('Y-m-d', strtotime($row->start_date)), $allDates))
                    continue;

                $allDates[date('Y-m-d', strtotime($row->start_date))] = [
                    'number' => $row->number,
                    'price' => $row->price,
                    'price2' => $row->price2
                ];
            }
        }

        $roomBookings = $this->getBookingsInRange($filters['start_date'], $filters['end_date']);
        if (!empty ($roomBookings)) {
            foreach ($roomBookings as $roomBooking) {
                $period = periodDate($roomBooking->start_date, $roomBooking->end_date, false);
                foreach ($period as $dt) {
                    $date = $dt->format('Y-m-d');
                    if (!array_key_exists($date, $allDates))
                        continue;
                    $allDates[$date]['number'] -= $roomBooking->number;
                    if ($allDates[$date]['number'] <= 0) {
                        return false;
                    }
                }
            }
        }

        if (!empty ($this->ical_import_url)) {
            $startDate = $filters['start_date'];
            $endDate = $filters['end_date'];
            $timezone = setting_item('site_timezone', config('app.timezone'));
            $icalevents = new Ical($this->ical_import_url, [
                'defaultTimeZone' => $timezone
            ]);
            $endDate = new \DateTime($endDate . ' +1 day');
            $eventRange = $icalevents->eventsFromRange($startDate, $endDate->format('Y/m/d'));
            // dd($eventRange);
            if (!empty ($eventRange)) {
                return false;
            }
        }

        if (!empty ($this->ical_import_url_2)) {
            $startDate = $filters['start_date'];
            $endDate = $filters['end_date'];
            $timezone = setting_item('site_timezone', config('app.timezone'));

            $icalevents = new Ical($this->ical_import_url_2, [
                'defaultTimeZone' => $timezone
            ]);
            $endDate = new \DateTime($endDate . ' +1 day');
            $eventRange = $icalevents->eventsFromRange($startDate, $endDate->format('Y/m/d'));
            if (!empty ($eventRange)) {
                return false;
            }
        }

        if (!empty ($this->ical_import_url_3)) {
            $startDate = $filters['start_date'];
            $endDate = $filters['end_date'];
            $timezone = setting_item('site_timezone', config('app.timezone'));
            $icalevents = new Ical($this->ical_import_url_3, [
                'defaultTimeZone' => $timezone
            ]);
            $endDate = new \DateTime($endDate . ' +1 day');
            $eventRange = $icalevents->eventsFromRange($startDate, $endDate->format('Y/m/d'));
            if (!empty ($eventRange)) {
                return false;
            }

        }

        if (!empty ($this->ical_import_url_4)) {
            $startDate = $filters['start_date'];
            $endDate = $filters['end_date'];
            $timezone = setting_item('site_timezone', config('app.timezone'));
            $icalevents = new Ical($this->ical_import_url_4, [
                'defaultTimeZone' => $timezone
            ]);
            $endDate = new \DateTime($endDate . ' +1 day');
            $eventRange = $icalevents->eventsFromRange($startDate, $endDate->format('Y/m/d'));
            if (!empty ($eventRange)) {
                return false;
            }
        }

        return true;
    }

    public function getDatesInRange($start_date, $end_date)
    {
        $query = $this->roomDateClass::query();
        $query->where('target_id', $this->id);
        $query->where('start_date', '>=', date('Y-m-d H:i:s', strtotime($start_date)));
        $query->where('end_date', '<=', date('Y-m-d H:i:s', strtotime($end_date)));

        return $query->take(100)->get();
    }

    public function getBookingsInRange($from, $to)
    {
        return $this->roomBookingClass::query()
            ->where('bravo_hotel_room_bookings.room_id', $this->id)
            ->active()
            ->inRange($from, $to)
            ->get(['bravo_hotel_room_bookings.*']);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'parent_id');
    }

}
