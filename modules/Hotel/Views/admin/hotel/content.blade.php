@php
use Modules\Hotel\Models\Hotel;
use App\Models\HotelCancelationPolicy;
$hotel_p = Hotel::find($translation->origin_id);
$policy_h = HotelCancelationPolicy::get();
@endphp
<div class="panel">
    <div class="panel-title"><strong>{{__("Hotel Content")}}</strong></div>
    <div class="panel-body">
        <div class="form-group">
            <label>{{__("Title")}}</label>
            <input type="text" value="{!! clean($translation->title) !!}" placeholder="{{__("Name of the hotel")}}" name="title" class="form-control">
        </div>
        <div class="form-group">
            <label class="control-label">{{__("Content")}}</label>
            <div class="">
                <textarea name="content" class="d-none has-ckeditor" cols="30" rows="10">{{$translation->content}}</textarea>
            </div>
        </div>
        
        @if($translation->locale != "ar")
        <div class="form-group">
            <label class="control-label text-danger font-weight-bold">{{__("Cancelation Policy")}} :</label>
            <div class="pl-2">
                @if(count($policy_h))
                @foreach($policy_h as $item)
                <div class="d-flex align-items-start">
                    <input {{ ($row->cancelation_id== $item->id)? "checked" : "" }} class="ml-3 mt-2" type="radio" value={{$item->id}} id="cancelation_id{{$item->id}}" name="cancelation_id">
                    <label for="cancelation_id{{$item->id}}">{{__($item->item)}}</label><br>
                </div>
                @endforeach
                @endif
            </div>
        </div>
        @endif
        
        @if(is_default_lang())
            <div class="form-group">
                <label class="control-label">{{__("Youtube Video")}}</label>
                <input type="text" name="video" class="form-control" value="{{$row->video}}" placeholder="{{__("Youtube link video")}}">
            </div>
        @endif
        @if(is_default_lang())
            <div class="form-group d-none">
                <label class="control-label">{{__("Banner Image")}}</label>
                <div class="form-group-image">
                    {!! \Modules\Media\Helpers\FileHelper::fieldUpload('banner_image_id',$row->banner_image_id) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">{{__("Gallery")}}</label>
                {!! \Modules\Media\Helpers\FileHelper::fieldGalleryUpload('gallery',$row->gallery) !!}
            </div>
            @if($hotel_p)
            <a href="{{route('sort.image.index', $hotel_p)}}" class="btn btn-info"><i class="fa fa-sort" aria-hidden="true"></i>
                Image Sort</a> <!--Sort image added by Assadaldin-->
        @endif
         @endif
    </div>
</div>

<div class="panel">
    <div class="panel-title"><strong>{{__("Hotel Policy")}}</strong></div>
    <div class="panel-body">
        <!--@if(is_default_lang())-->
        <!--    <div class="row">-->
        <!--        <div class="col-md-6">-->
        <!--            <div class="form-group">-->
        <!--                <label>{{__("Hotel rating standard")}}</label>-->
        <!--                <input type="number" value="{{$row->star_rate}}" placeholder="{{__("Eg: 5")}}" name="star_rate" class="form-control">-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--@endif-->
        <div class="form-group-item">
            <label class="control-label">{{__('Policy')}}</label>
            <div class="g-items-header">
                <div class="row">
                    <div class="col-md-5">{{__("Title")}}</div>
                    <div class="col-md-5">{{__('Content')}}</div>
                    <div class="col-md-1"></div>
                </div>
            </div>
            <div class="g-items">
                @if(!empty($translation->policy))
                    @foreach($translation->policy as $key=>$item)
                        <div class="item" data-number="{{$key}}">
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" name="policy[{{$key}}][title]" class="form-control" value="{{$item['title']}}" placeholder="{{__('Eg: What kind of foowear is most suitable ?')}}">
                                </div>
                                <div class="col-md-6">
                                    <textarea name="policy[{{$key}}][content]" class="form-control" placeholder="...">{{$item['content']}}</textarea>
                                </div>
                                <div class="col-md-1">
                                    <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="text-right">
                <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add item')}}</span>
            </div>
            <div class="g-more hide">
                <div class="item" data-number="__number__">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" __name__="policy[__number__][title]" class="form-control" placeholder="{{__('Eg: What kind of foowear is most suitable ?')}}">
                        </div>
                        <div class="col-md-6">
                            <textarea __name__="policy[__number__][content]" class="form-control" placeholder=""></textarea>
                        </div>
                        <div class="col-md-1">
                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php do_action(\Modules\Hotel\Hook::FORM_AFTER_POLICY,$row) ?>
    </div>
</div>
