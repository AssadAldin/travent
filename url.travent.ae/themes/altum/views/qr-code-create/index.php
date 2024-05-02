<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="d-print-none">
        <?php if(settings()->main->breadcrumbs_is_enabled): ?>
            <nav aria-label="breadcrumb">
                <ol class="custom-breadcrumbs small">
                    <li>
                        <a href="<?= url('qr-codes') ?>"><?= l('qr_codes.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                    </li>
                    <li class="active" aria-current="page"><?= l('qr_code_create.breadcrumb') ?></li>
                </ol>
            </nav>
        <?php endif ?>

        <div class="d-flex align-items-center mb-4">
            <h1 class="h4 text-truncate mb-0 mr-2"><i class="fas fa-fw fa-xs fa-qrcode mr-1"></i> <?= l('qr_code_create.header') ?></h1>
        </div>
    </div>

    <form action="" method="post" role="form" enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
        <input type="hidden" name="api_key" value="<?= $this->user->api_key ?>" />
        <input type="hidden" name="qr_code" value="<?= $data->values['qr_code'] ?? null ?>" />
        <input type="hidden" name="embedded_data" value="<?= $data->values['embedded_data'] ?? null ?>" />
        <input type="hidden" name="reload" value="" data-reload-qr-code />

        <div class="flex-wrap mb-4 btn-group-toggle d-none d-lg-flex" data-toggle="buttons">
            <?php foreach($data->qr_code_settings['type'] as $key => $value): ?>
                <label class="mr-3 mb-3 btn btn-light <?= $data->values['type'] == $key ? 'active' : null ?>" data-toggle="tooltip" title="<?= l('qr_codes.type.' . $key . '_description') ?>" data-tooltip-hide-on-click>
                    <input type="radio" name="type" value="<?= $key ?>" class="custom-control-input" <?= $data->values['type'] == $key ? 'checked="checked"' : null ?> required="required" data-reload-qr-code />
                    <i class="<?= $value['icon'] ?> fa-fw fa-sm mr-1"></i> <?= l('qr_codes.type.' . $key) ?>
                </label>
            <?php endforeach ?>
        </div>

        <div class="row">
            <div class="col-12 col-lg-6 d-print-none mb-5 mb-lg-0">
                <div class="card">
                    <div class="card-body">
                        <div class="notification-container"></div>

                        <div class="form-group">
                            <label for="name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('global.name') ?></label>
                            <input type="text" id="name" name="name" class="form-control <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->values['name'] ?? null ?>" maxlength="64" required="required" />
                            <?= \Altum\Alerts::output_field_error('name') ?>
                        </div>

                        <div class="form-group">
                            <div class="d-flex flex-column flex-xl-row justify-content-between">
                                <label for="project_id"><i class="fas fa-fw fa-sm fa-project-diagram text-muted mr-1"></i> <?= l('projects.project_id') ?></label>
                                <a href="<?= url('project-create') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('projects.create') ?></a>
                            </div>
                            <select id="project_id" name="project_id" class="custom-select">
                                <option value=""><?= l('global.none') ?></option>
                                <?php foreach($data->projects as $row): ?>
                                    <option value="<?= $row->project_id ?>" <?= ($data->values['project_id'] ?? null) == $row->project_id ? 'selected="selected"' : null?>><?= $row->name ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="form-group d-lg-none">
                            <label for="type"><i class="fas fa-fw fa-qrcode fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.type') ?></label>
                            <select id="type" name="type" class="custom-select">
                                <?php foreach(array_keys($data->qr_code_settings['type']) as $type): ?>
                                    <option value="<?= $type ?>" <?= ($data->values['type'] ?? null) == $type ? 'selected="selected"' : null ?>><?= $data->qr_code_settings['type'][$type]['emoji'] . ' ' . l('qr_codes.type.' . $type) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div>
                            <div class="form-group" data-type="text">
                                <label for="text"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.text') ?></label>
                                <textarea id="text" name="text" class="form-control <?= \Altum\Alerts::has_field_errors('text') ? 'is-invalid' : null ?>" maxlength="<?= $data->qr_code_settings['type']['text']['max_length'] ?>" required="required" data-reload-qr-code><?= $data->values['settings']['text'] ?? null ?></textarea>
                                <?= \Altum\Alerts::output_field_error('text') ?>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" data-type="url">
                                <label for="url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('global.url') ?></label>
                                <input type="url" id="url" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['url'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['url']['max_length'] ?>" required="required" placeholder="<?= l('global.url_placeholder') ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('url') ?>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" data-type="phone">
                                <label for="phone"><i class="fas fa-fw fa-phone-square-alt fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.phone') ?></label>
                                <input type="text" id="phone" name="phone" class="form-control <?= \Altum\Alerts::has_field_errors('phone') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['phone'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['phone']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('phone') ?>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" data-type="sms">
                                <label for="sms"><i class="fas fa-fw fa-sms fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.sms') ?></label>
                                <input type="text" id="sms" name="sms" class="form-control <?= \Altum\Alerts::has_field_errors('sms') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['sms'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['sms']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('sms') ?>
                            </div>

                            <div class="form-group" data-type="sms">
                                <label for="sms_body"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.sms_body') ?></label>
                                <textarea id="sms_body" name="sms_body" class="form-control <?= \Altum\Alerts::has_field_errors('sms_body') ? 'is-invalid' : null ?>" maxlength="<?= $data->qr_code_settings['type']['sms']['body']['max_length'] ?>" data-reload-qr-code><?= $data->values['settings']['sms_body'] ?? null ?></textarea>
                                <?= \Altum\Alerts::output_field_error('sms_body') ?>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" data-type="email">
                                <label for="email"><i class="fas fa-fw fa-envelope fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.email') ?></label>
                                <input type="text" id="email" name="email" class="form-control <?= \Altum\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['email'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['email']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('email') ?>
                            </div>

                            <div class="form-group" data-type="email">
                                <label for="email_subject"><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.email_subject') ?></label>
                                <input type="text" id="email_subject" name="email_subject" class="form-control <?= \Altum\Alerts::has_field_errors('email_subject') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['email_subject'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['email']['body']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('email_subject') ?>
                            </div>

                            <div class="form-group" data-type="email">
                                <label for="email_body"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.email_body') ?></label>
                                <textarea id="email_body" name="email_body" class="form-control <?= \Altum\Alerts::has_field_errors('email_body') ? 'is-invalid' : null ?>" maxlength="<?= $data->qr_code_settings['type']['email']['body']['max_length'] ?>" data-reload-qr-code><?= $data->values['settings']['email_body'] ?? null ?></textarea>
                                <?= \Altum\Alerts::output_field_error('email_body') ?>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" data-type="whatsapp">
                                <label for="whatsapp"><i class="fab fa-fw fa-whatsapp fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.whatsapp') ?></label>
                                <input type="text" id="whatsapp" name="whatsapp" class="form-control <?= \Altum\Alerts::has_field_errors('whatsapp') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['whatsapp'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['whatsapp']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('whatsapp') ?>
                            </div>

                            <div class="form-group" data-type="whatsapp">
                                <label for="whatsapp_body"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.whatsapp_body') ?></label>
                                <textarea id="whatsapp_body" name="whatsapp_body" class="form-control <?= \Altum\Alerts::has_field_errors('whatsapp_body') ? 'is-invalid' : null ?>" maxlength="<?= $data->qr_code_settings['type']['whatsapp']['body']['max_length'] ?>" data-reload-qr-code><?= $data->values['settings']['whatsapp_body'] ?? null ?></textarea>
                                <?= \Altum\Alerts::output_field_error('whatsapp_body') ?>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" data-type="facetime">
                                <label for="facetime"><i class="fas fa-fw fa-headset fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.facetime') ?></label>
                                <input type="text" id="facetime" name="facetime" class="form-control <?= \Altum\Alerts::has_field_errors('facetime') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['facetime'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['facetime']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('facetime') ?>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" data-type="location">
                                <label for="location_latitude"><i class="fas fa-fw fa-map-pin fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.location_latitude') ?></label>
                                <input type="number" id="location_latitude" name="location_latitude" step="0.0000001" class="form-control <?= \Altum\Alerts::has_field_errors('location_latitude') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['location_latitude'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['location']['latitude']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('location_latitude') ?>
                            </div>

                            <div class="form-group" data-type="location">
                                <label for="location_longitude"><i class="fas fa-fw fa-map-pin fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.location_longitude') ?></label>
                                <input type="number" id="location_longitude" name="location_longitude" step="0.0000001" class="form-control <?= \Altum\Alerts::has_field_errors('location_longitude') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['location_longitude'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['location']['longitude']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('location_longitude') ?>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" data-type="wifi">
                                <label for="wifi_ssid"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.wifi_ssid') ?></label>
                                <input type="text" id="wifi_ssid" name="wifi_ssid" class="form-control <?= \Altum\Alerts::has_field_errors('wifi_ssid') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['wifi_ssid'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['wifi']['ssid']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('wifi_ssid') ?>
                            </div>

                            <div class="form-group" data-type="wifi">
                                <label for="wifi_encryption"><i class="fas fa-fw fa-user-shield fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.wifi_encryption') ?></label>
                                <select id="wifi_encryption" name="wifi_encryption" class="custom-select" data-reload-qr-code>
                                    <option value="WEP" <?= ($data->values['settings']['wifi_encryption'] ?? null) == 'WEP' ? 'selected="selected"' : null ?>>WEP</option>
                                    <option value="WPA/WPA2" <?= ($data->values['settings']['wifi_encryption'] ?? null) == 'WPA/WPA2' ? 'selected="selected"' : null ?>>WPA/WPA2</option>
                                    <option value="nopass" <?= ($data->values['settings']['wifi_encryption'] ?? null) == 'nopass' ? 'selected="selected"' : null ?>><?= l('qr_codes.input.wifi_encryption_nopass') ?></option>
                                </select>
                            </div>

                            <div class="form-group" data-type="wifi">
                                <label for="wifi_password"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.wifi_password') ?></label>
                                <input type="text" id="wifi_password" name="wifi_password" class="form-control <?= \Altum\Alerts::has_field_errors('wifi_password') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['wifi_password'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['wifi']['password']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('wifi_password') ?>
                            </div>

                            <div class="form-group" data-type="wifi">
                                <label for="wifi_is_hidden"><i class="fas fa-fw fa-user-secret fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.wifi_is_hidden') ?></label>
                                <select id="wifi_is_hidden" name="wifi_is_hidden" class="custom-select" data-reload-qr-code>
                                    <option value="1" <?= $data->values['settings']['wifi_is_hidden'] ?? null ? 'selected="selected"' : null ?>><?= l('global.yes') ?></option>
                                    <option value="0" <?= $data->values['settings']['wifi_is_hidden'] ?? null ? 'selected="selected"' : null ?>><?= l('global.no') ?></option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" data-type="event">
                                <label for="event"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.event') ?></label>
                                <input type="text" id="event" name="event" class="form-control <?= \Altum\Alerts::has_field_errors('event') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['event'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['event']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('event') ?>
                            </div>

                            <div class="form-group" data-type="event">
                                <label for="event_location"><i class="fas fa-fw fa-map-pin fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.event_location') ?></label>
                                <input type="text" id="event_location" name="event_location" class="form-control <?= \Altum\Alerts::has_field_errors('event_location') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['event_location'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['event']['location']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('event_location') ?>
                            </div>

                            <div class="form-group" data-type="event">
                                <label for="event_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.event_url') ?></label>
                                <input type="url" id="event_url" name="event_url" class="form-control <?= \Altum\Alerts::has_field_errors('event_url') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['event_url'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['event']['url']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('event_url') ?>
                            </div>

                            <div class="form-group" data-type="event">
                                <label for="event_note"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.event_note') ?></label>
                                <textarea id="event_note" name="event_note" class="form-control <?= \Altum\Alerts::has_field_errors('event_note') ? 'is-invalid' : null ?>" maxlength="<?= $data->qr_code_settings['type']['event']['note']['max_length'] ?>" data-reload-qr-code><?= $data->values['settings']['event_note'] ?? null ?></textarea>
                                <?= \Altum\Alerts::output_field_error('event_note') ?>
                            </div>

                            <div class="form-group" data-type="event">
                                <label for="event_start_datetime"><i class="fas fa-fw fa-calendar fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.event_start_datetime') ?></label>
                                <input type="datetime-local" id="event_start_datetime" name="event_start_datetime" class="form-control <?= \Altum\Alerts::has_field_errors('event_start_datetime') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['event_start_datetime'] ?? null ?>" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('event_start_datetime') ?>
                            </div>

                            <div class="form-group" data-type="event">
                                <label for="event_end_datetime"><i class="fas fa-fw fa-calendar fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.event_end_datetime') ?></label>
                                <input type="datetime-local" id="event_end_datetime" name="event_end_datetime" class="form-control <?= \Altum\Alerts::has_field_errors('event_end_datetime') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['event_end_datetime'] ?? null ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('event_end_datetime') ?>
                            </div>

                            <div class="form-group" data-type="event">
                                <label for="event_timezone"><i class="fas fa-fw fa-atlas fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.event_timezone') ?></label>
                                <select id="event_timezone" name="event_timezone" class="custom-select" data-reload-qr-code>
                                    <?php foreach(DateTimeZone::listIdentifiers() as $timezone): ?>
                                        <option value="<?= $timezone ?>" <?= ($data->values['settings']['event_timezone'] ?? null) == $timezone ? 'selected="selected"' : null?>><?= $timezone ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" data-type="crypto">
                                <label for="crypto_coin"><i class="fab fa-fw fa-bitcoin fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.crypto_coin') ?></label>
                                <select id="crypto_coin" name="crypto_coin" class="custom-select" data-reload-qr-code>
                                    <?php foreach($data->qr_code_settings['type']['crypto']['coins'] as $coin => $coin_name): ?>
                                        <option value="<?= $coin ?>" <?= ($data->values['settings']['crypto_coin'] ?? null) == $coin ? 'selected="selected"' : null?>><?= $coin_name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group" data-type="crypto">
                                <label for="crypto_address"><i class="fas fa-fw fa-map-marker-alt fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.crypto_address') ?></label>
                                <input type="text" id="crypto_address" name="crypto_address" class="form-control <?= \Altum\Alerts::has_field_errors('crypto_address') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['crypto_address'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['crypto']['address']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('crypto_address') ?>
                            </div>

                            <div class="form-group" data-type="crypto">
                                <label for="crypto_amount"><i class="fas fa-fw fa-coins fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.crypto_amount') ?></label>
                                <input type="number" step="0.01" min="0.00000001" id="crypto_amount" name="crypto_amount" class="form-control <?= \Altum\Alerts::has_field_errors('crypto_amount') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['crypto_amount'] ?? null ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('crypto_address') ?>
                            </div>
                        </div>

                        <div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group" data-type="vcard">
                                        <label for="vcard_first_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.vcard_first_name') ?></label>
                                        <input type="text" id="vcard_first_name" name="vcard_first_name" class="form-control <?= \Altum\Alerts::has_field_errors('vcard_first_name') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['vcard_first_name'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['vcard']['first_name']['max_length'] ?>" data-reload-qr-code />
                                        <?= \Altum\Alerts::output_field_error('vcard_first_name') ?>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group" data-type="vcard">
                                        <label for="vcard_last_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.vcard_last_name') ?></label>
                                        <input type="text" id="vcard_last_name" name="vcard_last_name" class="form-control <?= \Altum\Alerts::has_field_errors('vcard_last_name') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['vcard_last_name'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['vcard']['last_name']['max_length'] ?>" data-reload-qr-code />
                                        <?= \Altum\Alerts::output_field_error('vcard_last_name') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" data-type="vcard">
                                <label for="vcard_email"><i class="fas fa-fw fa-envelope fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.vcard_email') ?></label>
                                <input type="email" id="vcard_email" name="vcard_email" class="form-control <?= \Altum\Alerts::has_field_errors('vcard_email') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['vcard_email'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['vcard']['email']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('vcard_email') ?>
                            </div>

                            <div class="form-group" data-type="vcard">
                                <label for="vcard_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.vcard_url') ?></label>
                                <input type="url" id="vcard_url" name="vcard_url" class="form-control <?= \Altum\Alerts::has_field_errors('vcard_url') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['vcard_url'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['vcard']['url']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('vcard_url') ?>
                            </div>

                            <div class="form-group" data-type="vcard">
                                <label for="vcard_company"><i class="fas fa-fw fa-building fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.vcard_company') ?></label>
                                <input type="text" id="vcard_company" name="vcard_company" class="form-control <?= \Altum\Alerts::has_field_errors('vcard_company') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['vcard_company'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['vcard']['company']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('vcard_company') ?>
                            </div>

                            <div class="form-group" data-type="vcard">
                                <label for="vcard_job_title"><i class="fas fa-fw fa-user-tie fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.vcard_job_title') ?></label>
                                <input type="text" id="vcard_job_title" name="vcard_job_title" class="form-control <?= \Altum\Alerts::has_field_errors('vcard_job_title') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['vcard_job_title'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['vcard']['job_title']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('vcard_job_title') ?>
                            </div>

                            <div class="form-group" data-type="vcard">
                                <label for="vcard_birthday"><i class="fas fa-fw fa-birthday-cake fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.vcard_birthday') ?></label>
                                <input type="date" id="vcard_birthday" name="vcard_birthday" class="form-control <?= \Altum\Alerts::has_field_errors('vcard_birthday') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['vcard_birthday'] ?? null ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('vcard_birthday') ?>
                            </div>

                            <div class="form-group" data-type="vcard">
                                <label for="vcard_street"><i class="fas fa-fw fa-road fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.vcard_street') ?></label>
                                <input type="text" id="vcard_street" name="vcard_street" class="form-control <?= \Altum\Alerts::has_field_errors('vcard_street') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['vcard_street'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['vcard']['street']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('vcard_street') ?>
                            </div>

                            <div class="form-group" data-type="vcard">
                                <label for="vcard_city"><i class="fas fa-fw fa-city fa-sm text-muted mr-1"></i> <?= l('global.city') ?></label>
                                <input type="text" id="vcard_city" name="vcard_city" class="form-control <?= \Altum\Alerts::has_field_errors('vcard_city') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['vcard_city'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['vcard']['city']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('vcard_city') ?>
                            </div>

                            <div class="form-group" data-type="vcard">
                                <label for="vcard_zip"><i class="fas fa-fw fa-mail-bulk fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.vcard_zip') ?></label>
                                <input type="text" id="vcard_zip" name="vcard_zip" class="form-control <?= \Altum\Alerts::has_field_errors('vcard_zip') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['vcard_zip'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['vcard']['zip']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('vcard_zip') ?>
                            </div>

                            <div class="form-group" data-type="vcard">
                                <label for="vcard_region"><i class="fas fa-fw fa-flag fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.vcard_region') ?></label>
                                <input type="text" id="vcard_region" name="vcard_region" class="form-control <?= \Altum\Alerts::has_field_errors('vcard_region') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['vcard_region'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['vcard']['region']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('vcard_region') ?>
                            </div>

                            <div class="form-group" data-type="vcard">
                                <label for="vcard_country"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('global.country') ?></label>
                                <input type="text" id="vcard_country" name="vcard_country" class="form-control <?= \Altum\Alerts::has_field_errors('vcard_country') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['vcard_country'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['vcard']['country']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('vcard_country') ?>
                            </div>

                            <div class="form-group" data-type="vcard">
                                <label for="vcard_note"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.vcard_note') ?></label>
                                <textarea id="vcard_note" name="vcard_note" class="form-control <?= \Altum\Alerts::has_field_errors('vcard_note') ? 'is-invalid' : null ?>" maxlength="<?= $data->qr_code_settings['type']['vcard']['note']['max_length'] ?>" data-reload-qr-code><?= $data->values['settings']['vcard_note'] ?? null ?></textarea>
                                <?= \Altum\Alerts::output_field_error('vcard_note') ?>
                            </div>

                            <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#vcard_phone_numbers_container" aria-expanded="false" aria-controls="vcard_phone_numbers_container" data-type="vcard">
                                <i class="fas fa-fw fa-phone-square-alt fa-sm mr-1"></i> <?= l('qr_codes.input.vcard_phone_numbers') ?>
                            </button>

                            <div class="collapse" id="vcard_phone_numbers_container" data-type="vcard">
                                <div id="vcard_phone_numbers">
                                    <?php foreach($data->values['settings']['vcard_phone_numbers'] ?? [] as $key => $phone_number): ?>
                                        <div class="mb-4">
                                            <div class="form-group">
                                                <label for="<?= 'vcard_phone_number_label_' . $key . '_' . $data->link->link_id ?>"><?= l('qr_codes.input.vcard_phone_number_label') ?></label>
                                                <input id="<?= 'vcard_phone_number_label_' . $key . '_' . $data->link->link_id ?>" type="text" name="vcard_phone_number_label[<?= $key ?>]" class="form-control" value="<?= $phone_number->label ?>" maxlength="<?= $data->qr_code_settings['type']['vcard']['phone_number_label']['max_length'] ?>" data-reload-qr-code />
                                            </div>

                                            <div class="form-group">
                                                <label for="<?= 'vcard_phone_number_value_' . $key . '_' . $data->link->link_id ?>"><?= l('qr_codes.input.vcard_phone_number_value') ?></label>
                                                <input id="<?= 'vcard_phone_number_value_' . $key . '_' . $data->link->link_id ?>" type="text" name="vcard_phone_number_value[<?= $key ?>]" value="<?= $phone_number->value ?>" class="form-control" maxlength="<?= $data->qr_code_settings['type']['vcard']['phone_number_value']['max_length'] ?>" required="required" data-reload-qr-code />
                                            </div>

                                            <button type="button" data-remove="vcard_phone_numbers" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
                                        </div>
                                    <?php endforeach ?>
                                </div>

                                <div class="mb-3">
                                    <button data-add="vcard_phone_numbers" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                                </div>
                            </div>

                            <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#vcard_socials_container" aria-expanded="false" aria-controls="vcard_socials_container" data-type="vcard">
                                <i class="fas fa-fw fa-share-alt fa-sm mr-1"></i> <?= l('qr_codes.input.vcard_socials') ?>
                            </button>

                            <div class="collapse" id="vcard_socials_container" data-type="vcard">
                                <div id="vcard_socials">
                                    <?php foreach($data->values['settings']['vcard_socials'] ?? [] as $key => $social): ?>
                                        <div class="mb-4">
                                            <div class="form-group" data-type="vcard">
                                                <label for="<?= 'vcard_social_label_' . $key ?>"><?= l('qr_codes.input.vcard_social_label') ?></label>
                                                <input id="<?= 'vcard_social_label_' . $key ?>" type="text" name="vcard_social_label[<?= $key ?>]" class="form-control" value="<?= $social->label ?>" maxlength="<?= $data->qr_code_settings['type']['vcard']['social_label']['max_length'] ?>" required="required" data-reload-qr-code />
                                            </div>

                                            <div class="form-group" data-type="vcard">
                                                <label for="<?= 'vcard_social_value_' . $key ?>"><?= l('qr_codes.input.vcard_social_value') ?></label>
                                                <input id="<?= 'vcard_social_value_' . $key ?>" type="url" name="vcard_social_value[<?= $key ?>]" value="<?= $social->value ?>" class="form-control" maxlength="<?= $data->qr_code_settings['type']['vcard']['social_value']['max_length'] ?>" required="required" data-reload-qr-code />
                                            </div>

                                            <button type="button" data-remove="vcard_social" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
                                        </div>
                                    <?php endforeach ?>
                                </div>

                                <div class="mb-3">
                                    <button data-add="vcard_social" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" data-type="paypal">
                                <label for="paypal_type"><i class="fab fa-fw fa-paypal fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.paypal_type') ?></label>
                                <select id="paypal_type" name="paypal_type" class="custom-select" data-reload-qr-code>
                                    <?php foreach($data->qr_code_settings['type']['paypal']['type'] as $key => $value): ?>
                                        <option value="<?= $key ?>" <?= ($data->values['settings']['paypal_type'] ?? null) == $key ? 'selected="selected"' : null?>><?= l('qr_codes.input.paypal_type_' . $key) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group" data-type="paypal">
                                <label for="paypal_email"><i class="fas fa-fw fa-envelope fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.paypal_email') ?></label>
                                <input type="email" id="paypal_email" name="paypal_email" class="form-control <?= \Altum\Alerts::has_field_errors('paypal_email') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['paypal_email'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['paypal']['email']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('paypal_email') ?>
                            </div>

                            <div class="form-group" data-type="paypal">
                                <label for="paypal_title"><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.paypal_title') ?></label>
                                <input type="text" id="paypal_title" name="paypal_title" class="form-control <?= \Altum\Alerts::has_field_errors('paypal_title') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['paypal_title'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['paypal']['title']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('paypal_title') ?>
                            </div>

                            <div class="form-group" data-type="paypal">
                                <label for="paypal_currency"><i class="fas fa-fw fa-euro-sign fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.paypal_currency') ?></label>
                                <input type="text" id="paypal_currency" name="paypal_currency" class="form-control <?= \Altum\Alerts::has_field_errors('paypal_currency') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['paypal_currency'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['paypal']['currency']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('paypal_currency') ?>
                            </div>

                            <div class="form-group" data-type="paypal">
                                <label for="paypal_price"><i class="fas fa-fw fa-dollar-sign fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.paypal_price') ?></label>
                                <input type="number" id="paypal_price" name="paypal_price" class="form-control <?= \Altum\Alerts::has_field_errors('paypal_price') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['paypal_price'] ?? null ?>" min="1" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('paypal_price') ?>
                            </div>

                            <div class="form-group" data-type="paypal">
                                <label for="paypal_thank_you_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.paypal_thank_you_url') ?></label>
                                <input type="text" id="paypal_thank_you_url" name="paypal_thank_you_url" class="form-control <?= \Altum\Alerts::has_field_errors('paypal_thank_you_url') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['paypal_thank_you_url'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['paypal']['thank_you_url']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('paypal_thank_you_url') ?>
                            </div>

                            <div class="form-group" data-type="paypal">
                                <label for="paypal_cancel_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.paypal_cancel_url') ?></label>
                                <input type="text" id="paypal_cancel_url" name="paypal_cancel_url" class="form-control <?= \Altum\Alerts::has_field_errors('paypal_cancel_url') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['paypal_cancel_url'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['paypal']['cancel_url']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('paypal_cancel_url') ?>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" data-type="upi">
                                <label for="upi_payee_id"><i class="fas fa-fw fa-fingerprint fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.upi_payee_id') ?></label>
                                <input type="text" id="upi_payee_id" name="upi_payee_id" class="form-control <?= \Altum\Alerts::has_field_errors('upi_payee_id') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['upi_payee_id'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['upi']['payee_id']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('upi_payee_id') ?>
                            </div>

                            <div class="form-group" data-type="upi">
                                <label for="upi_payee_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.upi_payee_name') ?></label>
                                <input type="text" id="upi_payee_name" name="upi_payee_name" class="form-control <?= \Altum\Alerts::has_field_errors('upi_payee_name') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['upi_payee_name'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['upi']['payee_name']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('upi_payee_name') ?>
                            </div>

                            <div class="form-group" data-type="upi">
                                <label for="upi_amount"><i class="fas fa-fw fa-money-bill fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.upi_amount') ?></label>
                                <input type="number" id="upi_amount" name="upi_amount" class="form-control <?= \Altum\Alerts::has_field_errors('upi_amount') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['upi_amount'] ?? null ?>" min="0" step="0.01" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('upi_amount') ?>
                            </div>

                            <div class="form-group" data-type="upi">
                                <label for="upi_currency"><i class="fas fa-fw fa-rupee-sign fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.upi_currency') ?></label>
                                <input type="text" id="upi_currency" name="upi_currency" class="form-control <?= \Altum\Alerts::has_field_errors('upi_currency') ? 'is-invalid' : null ?>" value="INR" maxlength="<?= $data->qr_code_settings['type']['upi']['currency']['max_length'] ?>" required="required" readonly="readonly" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('upi_currency') ?>
                            </div>

                            <div class="form-group" data-type="upi">
                                <label for="upi_transaction_id"><i class="fas fa-fw fa-id-card fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.upi_transaction_id') ?></label>
                                <input type="text" id="upi_transaction_id" name="upi_transaction_id" class="form-control <?= \Altum\Alerts::has_field_errors('upi_transaction_id') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['upi_transaction_id'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['upi']['transaction_id']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('upi_transaction_id') ?>
                            </div>

                            <div class="form-group" data-type="upi">
                                <label for="upi_transaction_reference"><i class="fas fa-fw fa-receipt fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.upi_transaction_reference') ?></label>
                                <input type="text" id="upi_transaction_reference" name="upi_transaction_reference" class="form-control <?= \Altum\Alerts::has_field_errors('upi_transaction_reference') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['upi_transaction_reference'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['upi']['transaction_reference']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('upi_transaction_reference') ?>
                            </div>

                            <div class="form-group" data-type="upi">
                                <label for="upi_transaction_note"><i class="fas fa-fw fa-sticky-note fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.upi_transaction_note') ?></label>
                                <input type="text" id="upi_transaction_note" name="upi_transaction_note" class="form-control <?= \Altum\Alerts::has_field_errors('upi_transaction_note') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['upi_transaction_note'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['upi']['transaction_note']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('upi_transaction_note') ?>
                            </div>

                            <div class="form-group" data-type="upi">
                                <label for="upi_thank_you_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.upi_thank_you_url') ?></label>
                                <input type="url" id="upi_thank_you_url" name="upi_thank_you_url" class="form-control <?= \Altum\Alerts::has_field_errors('upi_thank_you_url') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['upi_thank_you_url'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['upi']['thank_you_url']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('upi_thank_you_url') ?>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" data-type="epc">
                                <label for="epc_iban"><i class="fas fa-fw fa-fingerprint fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.epc_iban') ?></label>
                                <input type="text" id="epc_iban" name="epc_iban" class="form-control <?= \Altum\Alerts::has_field_errors('epc_iban') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['epc_iban'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['epc']['iban']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('epc_iban') ?>
                            </div>

                            <div class="form-group" data-type="epc">
                                <label for="epc_payee_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.epc_payee_name') ?></label>
                                <input type="text" id="epc_payee_name" name="epc_payee_name" class="form-control <?= \Altum\Alerts::has_field_errors('epc_payee_name') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['epc_payee_name'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['epc']['payee_name']['max_length'] ?>" required="required" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('epc_payee_name') ?>
                            </div>

                            <div class="form-group" data-type="epc">
                                <label for="epc_amount"><i class="fas fa-fw fa-money-bill fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.epc_amount') ?></label>
                                <input type="number" id="epc_amount" name="epc_amount" class="form-control <?= \Altum\Alerts::has_field_errors('epc_amount') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['epc_amount'] ?? null ?>" min="0" step="0.01" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('epc_amount') ?>
                            </div>

                            <div class="form-group" data-type="epc">
                                <label for="epc_currency"><i class="fas fa-fw fa-euro-sign fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.epc_currency') ?></label>
                                <input type="text" id="epc_currency" name="epc_currency" class="form-control <?= \Altum\Alerts::has_field_errors('epc_currency') ? 'is-invalid' : null ?>" value="EUR" maxlength="<?= $data->qr_code_settings['type']['epc']['currency']['max_length'] ?>" required="required" readonly="readonly" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('epc_currency') ?>
                            </div>

                            <div class="form-group" data-type="epc">
                                <label for="epc_bic"><i class="fas fa-fw fa-id-card fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.epc_bic') ?></label>
                                <input type="text" id="epc_bic" name="epc_bic" class="form-control <?= \Altum\Alerts::has_field_errors('epc_bic') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['epc_bic'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['epc']['bic']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('epc_bic') ?>
                            </div>

                            <div class="form-group" data-type="epc">
                                <label for="epc_remittance_reference"><i class="fas fa-fw fa-receipt fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.epc_remittance_reference') ?></label>
                                <input type="text" id="epc_remittance_reference" name="epc_remittance_reference" class="form-control <?= \Altum\Alerts::has_field_errors('epc_remittance_reference') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['epc_remittance_reference'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['epc']['remittance_reference']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('epc_remittance_reference') ?>
                            </div>

                            <div class="form-group" data-type="epc">
                                <label for="epc_remittance_text"><i class="fas fa-fw fa-sticky-note fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.epc_remittance_text') ?></label>
                                <input type="text" id="epc_remittance_text" name="epc_remittance_text" class="form-control <?= \Altum\Alerts::has_field_errors('epc_remittance_text') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['epc_remittance_text'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['epc']['remittance_text']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('epc_remittance_text') ?>
                            </div>

                            <div class="form-group" data-type="epc">
                                <label for="epc_information"><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.epc_information') ?></label>
                                <input type="text" id="epc_information" name="epc_information" class="form-control <?= \Altum\Alerts::has_field_errors('epc_information') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['epc_information'] ?? null ?>" maxlength="<?= $data->qr_code_settings['type']['epc']['information']['max_length'] ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('epc_information') ?>
                            </div>
                        </div>

                        <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#style_container" aria-expanded="false" aria-controls="style_container">
                            <i class="fas fa-fw fa-qrcode fa-sm mr-1"></i> <?= l('qr_codes.input.style') ?>
                        </button>

                        <div class="collapse" id="style_container">
                            <div class="form-group">
                                <label for="style"><i class="fas fa-fw fa-qrcode fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.style') ?></label>
                                <div class="row btn-group-toggle" data-toggle="buttons">
                                    <?php foreach(['square' => 'fas fa-square-full', 'dot' => 'fas fa-circle', 'round' => 'fas fa-square', 'heart' => 'fas fa-heart', 'diamond' => 'fas fa-gem'] as $key => $icon): ?>
                                        <div class="col-6">
                                            <label class="btn btn-light btn-block <?= ($data->values['settings']['style'] ?? null) == $key ? 'active"' : null?>">
                                                <input type="radio" name="style" value="<?= $key ?>" class="custom-control-input" <?= ($data->values['settings']['style'] ?? null) == $key ? 'checked="checked"' : null?> required="required" data-reload-qr-code />
                                                <i class="<?= $icon ?> fa-fw fa-sm mr-1"></i> <?= l('qr_codes.input.style.' . $key) ?>
                                            </label>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inner_eye_style"><i class="fas fa-fw fa-circle fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.inner_eye_style') ?></label>
                                <div class="row btn-group-toggle" data-toggle="buttons">
                                    <?php foreach(['square' => 'fas fa-square-full', 'dot' => 'fas fa-circle', 'rounded' => 'fas fa-square', 'diamond' => 'fas fa-gem', 'flower' => 'fas fa-seedling', 'leaf' => 'fas fa-leaf'] as $key => $icon): ?>
                                        <div class="col-6">
                                            <label class="btn btn-light btn-block <?= ($data->values['settings']['inner_eye_style'] ?? null) == $key ? 'active"' : null?>">
                                                <input type="radio" name="inner_eye_style" value="<?= $key ?>" class="custom-control-input" <?= ($data->values['settings']['inner_eye_style'] ?? null) == $key ? 'checked="checked"' : null?> required="required" data-reload-qr-code />
                                                <i class="<?= $icon ?> fa-fw fa-sm mr-1"></i> <?= l('qr_codes.input.style.' . $key) ?>
                                            </label>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="outer_eye_style"><i class="fas fa-fw fa-dot-circle fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.outer_eye_style') ?></label>
                                <div class="row btn-group-toggle" data-toggle="buttons">
                                    <?php foreach(['square' => 'fas fa-square-full', 'circle' => 'fas fa-circle', 'rounded' => 'fas fa-square', 'flower' => 'fas fa-seedling', 'leaf' => 'fas fa-leaf'] as $key => $icon): ?>
                                        <div class="col-6">
                                            <label class="btn btn-light btn-block <?= ($data->values['settings']['outer_eye_style'] ?? null) == $key ? 'active"' : null?>">
                                                <input type="radio" name="outer_eye_style" value="<?= $key ?>" class="custom-control-input" <?= ($data->values['settings']['outer_eye_style'] ?? null) == $key ? 'checked="checked"' : null?> required="required" data-reload-qr-code />
                                                <i class="<?= $icon ?> fa-fw fa-sm mr-1"></i> <?= l('qr_codes.input.style.' . $key) ?>
                                            </label>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#colors_container" aria-expanded="false" aria-controls="colors_container">
                            <i class="fas fa-fw fa-palette fa-sm mr-1"></i> <?= l('qr_codes.input.colors') ?>
                        </button>

                        <div class="collapse" id="colors_container">
                            <div class="form-group">
                                <label for="foreground_type"><i class="fas fa-fw fa-paint-roller fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.foreground_type') ?></label>
                                <div class="row btn-group-toggle" data-toggle="buttons">
                                    <div class="col-6">
                                        <label class="btn btn-light btn-block <?= ($data->values['settings']['foreground_type'] ?? null) == 'color' ? 'active"' : null?>">
                                            <input type="radio" name="foreground_type" value="color" class="custom-control-input" <?= ($data->values['settings']['foreground_type'] ?? null) == 'color' ? 'checked="checked"' : null?> required="required" data-reload-qr-code />
                                            <?= l('qr_codes.input.foreground_type_color') ?>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <label class="btn btn-light btn-block <?= ($data->values['settings']['foreground_type'] ?? null) == 'gradient' ? 'active' : null?>">
                                            <input type="radio" name="foreground_type" value="gradient" class="custom-control-input" <?= ($data->values['settings']['foreground_type'] ?? null) == 'gradient' ? 'checked="checked"' : null?> required="required" data-reload-qr-code />
                                            <?= l('qr_codes.input.foreground_type_gradient') ?>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" data-foreground-type="color">
                                <label for="foreground_color"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.foreground_color') ?></label>
                                <input type="hidden" id="foreground_color" name="foreground_color" class="form-control <?= \Altum\Alerts::has_field_errors('foreground_color') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['foreground_color'] ?? '#000000' ?>" data-reload-qr-code data-color-picker />
                                <?= \Altum\Alerts::output_field_error('foreground_color') ?>
                            </div>

                            <div class="form-group" data-foreground-type="gradient">
                                <label for="foreground_gradient_style"><i class="fas fa-fw fa-brush fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.foreground_gradient_style') ?></label>
                                <select id="foreground_gradient_style" name="foreground_gradient_style" class="custom-select" data-reload-qr-code>
                                    <?php foreach(['vertical', 'horizontal', 'diagonal', 'inverse_diagonal', 'radial'] as $style): ?>
                                        <option value="<?= $style ?>" <?= ($data->values['settings']['foreground_gradient_style'] ?? null) == $style ? 'selected="selected"' : null?>><?= l('qr_codes.input.foreground_gradient_style_' . $style) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group" data-foreground-type="gradient">
                                <label for="foreground_gradient_one"><?= l('qr_codes.input.foreground_gradient_one') ?></label>
                                <input type="hidden" id="foreground_gradient_one" name="foreground_gradient_one" class="form-control <?= \Altum\Alerts::has_field_errors('foreground_gradient_one') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['foreground_gradient_one'] ?? '#000000' ?>" data-reload-qr-code data-color-picker />
                                <?= \Altum\Alerts::output_field_error('foreground_gradient_one') ?>
                            </div>

                            <div class="form-group" data-foreground-type="gradient">
                                <label for="foreground_gradient_two"><?= l('qr_codes.input.foreground_gradient_two') ?></label>
                                <input type="hidden" id="foreground_gradient_two" name="foreground_gradient_two" class="form-control <?= \Altum\Alerts::has_field_errors('foreground_gradient_two') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['foreground_gradient_two'] ?? '#000000' ?>" data-reload-qr-code data-color-picker />
                                <?= \Altum\Alerts::output_field_error('foreground_gradient_two') ?>
                            </div>

                            <div class="form-group">
                                <label for="background_color"><?= l('qr_codes.input.background_color') ?></label>
                                <input type="hidden" id="background_color" name="background_color" class="form-control <?= \Altum\Alerts::has_field_errors('background_color') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['background_color'] ?? '#ffffff' ?>" data-reload-qr-code data-color-picker />
                                <?= \Altum\Alerts::output_field_error('background_color') ?>
                            </div>

                            <div class="form-group" data-range-counter data-range-counter-suffix="%">
                                <label for="background_color_transparency"><i class="fas fa-fw fa-lightbulb fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.background_color_transparency') ?></label>
                                <input id="background_color_transparency" type="range" min="0" max="100" step="10" name="background_color_transparency" value="<?= $data->values['settings']['background_color_transparency'] ?? 0 ?>" class="form-control <?= \Altum\Alerts::has_field_errors('background_color_transparency') ? 'is-invalid' : null ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('background_color_transparency') ?>
                            </div>

                            <div class="form-group custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="custom_eyes_color" name="custom_eyes_color" <?= (int) ($data->values['settings']['custom_eyes_color'] ?? null) ? 'checked="checked"' : null?> data-reload-qr-code />
                                <label class="custom-control-label" for="custom_eyes_color">
                                    <i class="fas fa-fw fa-eye fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.custom_eyes_color') ?>
                                </label>
                            </div>

                            <div class="form-group" data-custom-eyes-color="on">
                                <label for="eyes_inner_color"><?= l('qr_codes.input.eyes_inner_color') ?></label>
                                <input type="hidden" id="eyes_inner_color" name="eyes_inner_color" class="form-control <?= \Altum\Alerts::has_field_errors('eyes_inner_color') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['eyes_inner_color'] ?? '#000000' ?>" data-reload-qr-code data-color-picker />
                                <?= \Altum\Alerts::output_field_error('eyes_inner_color') ?>
                            </div>

                            <div class="form-group" data-custom-eyes-color="on">
                                <label for="eyes_outer_color"><?= l('qr_codes.input.eyes_outer_color') ?></label>
                                <input type="hidden" id="eyes_outer_color" name="eyes_outer_color" class="form-control <?= \Altum\Alerts::has_field_errors('eyes_outer_color') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['eyes_outer_color'] ?? '#000000' ?>" data-reload-qr-code data-color-picker />
                                <?= \Altum\Alerts::output_field_error('eyes_outer_color') ?>
                            </div>
                        </div>

                        <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#frame_container" aria-expanded="false" aria-controls="frame_container">
                            <i class="fas fa-fw fa-crop-alt fa-sm mr-1"></i> <?= l('qr_codes.input.frame') ?>
                        </button>

                        <div class="collapse" id="frame_container">

                            <div class="form-group">
                                <label for="frame"><i class="fas fa-fw fa-qrcode fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.frame') ?></label>
                                <div class="row btn-group-toggle" data-toggle="buttons">
                                    <div class="col-6 col-lg-4 mb-3">
                                        <label class="btn btn-light btn-block d-flex align-items-center justify-content-center <?= !($data->values['settings']['frame']) ? 'active"' : null?>" data-toggle="tooltip" data-tooltip-hide-on-click title="<?= l('global.none') ?>" style="height: 125px;">
                                            <input type="radio" name="frame" value="" class="custom-control-input" <?= !($data->values['settings']['frame']) ? 'checked="checked"' : null?> required="required" data-reload-qr-code />
                                            <i class="fas fa-fw fa-3x fa-times"></i>
                                        </label>
                                    </div>

                                    <?php foreach($data->frames as $key => $frame): ?>
                                        <div class="col-6 col-lg-4 mb-3">
                                            <label class="btn btn-light btn-block d-flex align-items-center justify-content-center <?= ($data->values['settings']['frame']) == $key ? 'active"' : null?>" style="height: 125px;">
                                                <input type="radio" name="frame" value="<?= $key ?>" class="custom-control-input" <?= ($data->values['settings']['frame']) == $key ? 'checked="checked"' : null?> required="required" data-reload-qr-code />
                                                <?= sprintf($frame['svg'], 75, 75 * $frame['frame_height_scale'], 75 / $frame['frame_scale'], 'var(--gray-900)') ?>
                                            </label>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="frame_text"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.frame_text') ?></label>
                                <input type="text" id="frame_text" name="frame_text" class="form-control <?= \Altum\Alerts::has_field_errors('frame_text') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['frame_text'] ?>" maxlength="64" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('frame_text') ?>
                            </div>

                            <div class="form-group" data-range-counter>
                                <label for="frame_text_size"><i class="fas fa-fw fa-text-height fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.frame_text_size') ?></label>
                                <input id="frame_text_size" type="range" min="-5" max="5" name="frame_text_size" value="<?= $data->values['settings']['frame_text_size'] ?>" class="form-control <?= \Altum\Alerts::has_field_errors('frame_text_size') ? 'is-invalid' : null ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('frame_text_size') ?>
                            </div>

                            <div class="form-group">
                                <label for="frame_text_font"><i class="fas fa-fw fa-pen-nib fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.frame_text_font') ?></label>
                                <div class="row btn-group-toggle" data-toggle="buttons">
                                    <?php foreach($data->frames_fonts as $font_key => $font): ?>
                                        <div class="col-12 col-lg-4 h-100">
                                            <label class="btn btn-light btn-block text-truncate <?= $data->values['settings']['frame_text_font'] == $font_key ? 'active"' : null?>" style="font-family: <?= $font['font-family'] ?> !important;">
                                                <input type="radio" name="frame_text_font" value="<?= $font_key ?>" class="custom-control-input" <?= $data->values['settings']['frame_text_font'] == $font_key ? 'checked="checked"' : null?> required="required" data-reload-qr-code />
                                                <?= $font['name'] ?>
                                            </label>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>

                            <div class="form-group custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="frame_custom_colors" name="frame_custom_colors" <?= (int) ($data->values['settings']['frame_custom_colors']) ? 'checked="checked"' : null?> data-reload-qr-code />
                                <label class="custom-control-label" for="frame_custom_colors">
                                    <i class="fas fa-fw fa-tint fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.frame_custom_colors') ?>
                                </label>
                            </div>

                            <div class="form-group" data-frame-custom-colors="on">
                                <label for="frame_color"><?= l('qr_codes.input.frame_color') ?></label>
                                <input type="hidden" id="frame_color" name="frame_color" class="form-control <?= \Altum\Alerts::has_field_errors('frame_color') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['frame_color'] ?>" data-reload-qr-code data-color-picker />
                                <?= \Altum\Alerts::output_field_error('frame_color') ?>
                            </div>

                            <div class="form-group" data-frame-custom-colors="on">
                                <label for="frame_text_color"><?= l('qr_codes.input.frame_text_color') ?></label>
                                <input type="hidden" id="frame_text_color" name="frame_text_color" class="form-control <?= \Altum\Alerts::has_field_errors('frame_text_color') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['frame_text_color'] ?>" data-reload-qr-code data-color-picker />
                                <?= \Altum\Alerts::output_field_error('frame_text_color') ?>
                            </div>
                        </div>

                        <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#branding_container" aria-expanded="false" aria-controls="branding_container">
                            <i class="fas fa-fw fa-copyright fa-sm mr-1"></i> <?= l('qr_codes.input.branding') ?>
                        </button>

                        <div class="collapse" id="branding_container">
                            <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= $data->qr_code_settings['qr_code_logo_size_limit'] ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), $data->qr_code_settings['qr_code_logo_size_limit']) ?>">
                                <label for="qr_code_logo"><i class="fas fa-fw fa-sm fa-eye text-muted mr-1"></i> <?= l('qr_codes.input.qr_code_logo') ?></label>
                                <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'qr_code_logo', 'file_key' => 'qr_code_logo', 'already_existing_image' => null, 'input_data' => 'data-reload-qr-code']) ?>
                                <?= \Altum\Alerts::output_field_error('qr_code_logo') ?>
                                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('qr_code_logo')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), $data->qr_code_settings['qr_code_logo_size_limit']) ?></small>
                            </div>

                            <div class="form-group" data-range-counter>
                                <label for="qr_code_logo_size"><i class="fas fa-fw fa-expand-alt fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.qr_code_logo_size') ?></label>
                                <input id="qr_code_logo_size" type="range" min="5" max="40" name="qr_code_logo_size" value="<?= $data->values['settings']['qr_code_logo_size'] ?? 25 ?>" class="form-control <?= \Altum\Alerts::has_field_errors('qr_code_logo_size') ? 'is-invalid' : null ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('qr_code_logo_size') ?>
                            </div>

                            <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= $data->qr_code_settings['qr_code_background_size_limit'] ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), $data->qr_code_settings['qr_code_logo_size_limit']) ?>">
                                <label for="qr_code_background"><i class="fas fa-fw fa-sm fa-image text-muted mr-1"></i> <?= l('qr_codes.input.qr_code_background') ?></label>
                                <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'qr_code_background', 'file_key' => 'qr_code_background', 'already_existing_image' => null, 'input_data' => 'data-reload-qr-code']) ?>
                                <?= \Altum\Alerts::output_field_error('qr_code_background') ?>
                                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('qr_code_background')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), $data->qr_code_settings['qr_code_background_size_limit']) ?></small>
                            </div>

                            <div class="form-group" data-range-counter data-range-counter-suffix="%">
                                <label for="qr_code_background_transparency"><i class="fas fa-fw fa-lightbulb fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.qr_code_background_transparency') ?></label>
                                <input id="qr_code_background_transparency" type="range" min="0" max="100" step="10" name="qr_code_background_transparency" value="<?= $data->values['settings']['qr_code_background_transparency'] ?? 0 ?>" class="form-control <?= \Altum\Alerts::has_field_errors('qr_code_background_transparency') ? 'is-invalid' : null ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('qr_code_background_transparency') ?>
                            </div>
                        </div>

                        <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#options_container" aria-expanded="false" aria-controls="options_container">
                            <i class="fas fa-fw fa-wrench fa-sm mr-1"></i> <?= l('qr_codes.input.options') ?>
                        </button>

                        <div class="collapse" id="options_container">
                            <div class="form-group">
                                <label for="size"><i class="fas fa-fw fa-expand-arrows-alt fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.size') ?></label>
                                <div class="input-group">
                                    <input id="size" type="number" min="50" max="2000" name="size" class="form-control <?= \Altum\Alerts::has_field_errors('size') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['size'] ?? 500 ?>" data-reload-qr-code />
                                    <div class="input-group-append">
                                        <span class="input-group-text">px</span>
                                    </div>
                                </div>
                                <?= \Altum\Alerts::output_field_error('size') ?>
                            </div>

                            <div class="form-group">
                                <label for="margin"><i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.margin') ?></label>
                                <input id="margin" type="number" min="0" max="25" name="margin" class="form-control <?= \Altum\Alerts::has_field_errors('margin') ? 'is-invalid' : null ?>" value="<?= $data->values['settings']['margin'] ?? 0 ?>" data-reload-qr-code />
                                <?= \Altum\Alerts::output_field_error('margin') ?>
                            </div>

                            <div class="form-group">
                                <label for="ecc"><i class="fas fa-fw fa-check fa-sm text-muted mr-1"></i> <?= l('qr_codes.input.ecc') ?></label>
                                <select id="ecc" name="ecc" class="custom-select" data-reload-qr-code>
                                    <?php foreach(['L', 'M', 'Q', 'H'] as $level): ?>
                                        <option value="<?= $level ?>" <?= ($data->values['settings']['ecc'] ?? null) == $level ? 'selected="selected"' : null ?>><?= l('qr_codes.input.ecc_' . mb_strtolower($level)) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <button type="submit" name="submit" class="btn btn-block btn-primary mt-4"><?= l('global.create') ?></button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="sticky">
                    <div class="mb-4">
                        <div class="card">
                            <div class="card-body">
                                <img id="qr_code" src="<?= ASSETS_FULL_URL . 'images/qr_code.svg' ?>" class="img-fluid qr-code" loading="lazy" />
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4 d-print-none">
                        <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                            <button type="button" onclick="window.print()" class="btn btn-block btn-outline-secondary d-print-none">
                                <i class="fas fa-fw fa-sm fa-file-pdf mr-1"></i> <?= l('qr_codes.print') ?>
                            </button>
                        </div>

                        <div class="col-12 col-lg-6 mb-3 mb-lg-0 dropdown">
                            <button type="button" class="btn btn-block btn-primary d-print-none dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-fw fa-sm fa-download mr-1"></i> <?= l('global.download') ?>
                            </button>

                            <div class="dropdown-menu">
                                <a href="<?= $data->values['settings']['data'] ?? ASSETS_FULL_URL . 'images/qr_code.svg' ?>" id="download_svg" class="dropdown-item" download="<?= get_slug($data->values['name'] ?? settings()->main->title) . '.svg' ?>"><?= sprintf(l('global.download_as'), 'SVG') ?></a>
                                <button type="button" class="dropdown-item" onclick="convert_svg_to_others(null, 'png', '<?= get_slug($data->values['name'] ?? settings()->main->title) . '.png' ?>');"><?= sprintf(l('global.download_as'), 'PNG') ?></button>
                                <button type="button" class="dropdown-item" onclick="convert_svg_to_others(null, 'jpg', '<?= get_slug($data->values['name'] ?? settings()->main->title) . '.jpg' ?>');"><?= sprintf(l('global.download_as'), 'JPG') ?></button>
                                <button type="button" class="dropdown-item" onclick="convert_svg_to_others(null, 'webp', '<?= get_slug($data->values['name'] ?? settings()->main->title) . '.webp' ?>');"><?= sprintf(l('global.download_as'), 'WEBP') ?></button>
                            </div>
                        </div>
                    </div>

                    <button id="embedded_data_container_button" class="btn btn-block btn-light my-4 d-none d-print-none" type="button" data-toggle="collapse" data-target="#embedded_data_container" aria-expanded="false" aria-controls="embedded_data_container">
                        <i class="fas fa-fw fa-bars fa-sm mr-1"></i> <?= l('qr_codes.embedded_data') ?>
                    </button>

                    <div class="collapse" id="embedded_data_container">
                        <div class="card my-4">
                            <div class="card-body" id="embedded_data_display"></div>
                        </div>
                    </div>

                    <div class="mb-4 text-center d-print-none">
                        <small>
                            <i class="fas fa-fw fa-info-circle text-muted mr-1"></i> <span class="text-muted"><?= l('qr_codes.info') ?></span>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<template id="template_vcard_social">
    <div class="mb-4">
        <div class="form-group">
            <label for=""><?= l('qr_codes.input.vcard_social_label') ?></label>
            <input id="" type="text" name="vcard_social_label[]" class="form-control" maxlength="<?= $data->qr_code_settings['type']['vcard']['social_label']['max_length'] ?>" required="required" data-reload-qr-code />
        </div>

        <div class="form-group">
            <label for=""><?= l('qr_codes.input.vcard_social_value') ?></label>
            <input id="" type="url" name="vcard_social_value[]" class="form-control" maxlength="<?= $data->qr_code_settings['type']['vcard']['social_value']['max_length'] ?>" required="required" data-reload-qr-code />
        </div>

        <button type="button" data-remove="vcard_social" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
    </div>
</template>

<template id="template_vcard_phone_numbers">
    <div class="mb-4">
        <div class="form-group">
            <label for=""><?= l('qr_codes.input.vcard_phone_number_label') ?></label>
            <input id="" type="text" name="vcard_phone_number_label[]" class="form-control" maxlength="<?= $data->qr_code_settings['type']['vcard']['phone_number_label']['max_length'] ?>" data-reload-qr-code />
        </div>

        <div class="form-group">
            <label for=""><?= l('qr_codes.input.vcard_phone_number_value') ?></label>
            <input id="" type="text" name="vcard_phone_number_value[]" class="form-control" maxlength="<?= $data->qr_code_settings['type']['vcard']['phone_number_value']['max_length'] ?>" required="required" data-reload-qr-code />
        </div>

        <button type="button" data-remove="vcard_phone_numbers" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
    </div>
</template>

<?php require THEME_PATH . 'views/qr-codes/js_qr_code.php' ?>
<?php include_view(THEME_PATH . 'views/partials/color_picker_js.php') ?>
