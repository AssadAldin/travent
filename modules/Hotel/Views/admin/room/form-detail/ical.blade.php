<div class="form-group">
    <label>{{ __('Import url1') }}</label>
    <input type="text" value="{{ $row->ical_import_url }}" name="ical_import_url" class="form-control">
</div>
<div class="form-group">
    <label>{{ __('Import url2') }}</label>
    <input type="text" value="{{ $row->ical_import_url_2 }}" name="ical_import_url2" class="form-control">
</div>
<div class="form-group">
    <label>{{ __('Import url3') }}</label>
    <input type="text" value="{{ $row->ical_import_url_3 }}" name="ical_import_url3" class="form-control">
</div>
<div class="form-group">
    <label>{{ __('Import url4') }}</label>
    <input type="text" value="{{ $row->ical_import_url_4 }}" name="ical_import_url4" class="form-control">
</div>
@if (!empty($row->id))
    <div class="form-group">
        <label>{{ __('Export url') }}</label>
        <input type="text" value="{{ route('booking.admin.export-ical', ['type' => 'room', $row->id]) }}"
            class="form-control">
    </div>
@endif
