<div
    class="bravo-form-search-all mt-0 pt-0 {{$style}} @if(!empty($style) and $style == "carousel") bravo-form-search-slider @endif"
    @if(empty($style))  @endif>
    @if(in_array($style,['carousel','']))
        @include("Template::frontend.blocks.form-search-all-service.style-normal")
    @endif
    @if($style == "carousel_v2")
        @include("Template::frontend.blocks.form-search-all-service.style-slider-ver2")
    @endif
</div>
