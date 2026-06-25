<script>
    @php
        $adminAsyncTaskListUrl = '';
        try {
            if (class_exists(\Modules\Log\Models\SystemLogRecord::class) && \Illuminate\Support\Facades\Schema::hasTable((new \Modules\Log\Models\SystemLogRecord())->getTable())) {
                $adminAsyncTaskListUrl = url('admin/log/asyncTaskList');
            }
        } catch (\Throwable $exception) {
            $adminAsyncTaskListUrl = '';
        }
    @endphp
    checkcms();
    const adminCmsLang = {
        clearCacheLoading: @json(getTranslateByKey('clear_cache_loading')),
        backupVersionLoading: @json(getTranslateByKey('backup_version_loading')),
        downloadLatestVersionLoading: @json(getTranslateByKey('download_latest_version_loading')),
        upgradeVersionLoading: @json(getTranslateByKey('upgrade_version_loading')),
        networkError: @json(getTranslateByKey('network_error')),
        sureToUpdateVersion: @json(getTranslateByKey('common_sure_to_update_version')),
        commonTip: @json(getTranslateByKey('common_tip')),
        commonEnsure: @json(getTranslateByKey('common_ensure')),
        commonCancel: @json(getTranslateByKey('common_cancel')),
        updatePreparing: @json(getTranslateByKey('update_preparing')),
        updateCurrentStage: @json(getTranslateByKey('update_current_stage')),
        updateTargetVersion: @json(getTranslateByKey('update_target_version')),
        updatePackageSize: @json(getTranslateByKey('update_package_size')),
        updateStagePrepare: @json(getTranslateByKey('update_stage_prepare')),
        updateStageDownload: @json(getTranslateByKey('update_stage_download')),
        updateStageUnzip: @json(getTranslateByKey('update_stage_unzip')),
        updateStageFinish: @json(getTranslateByKey('update_stage_finish')),
        updateNoSizeHint: @json(getTranslateByKey('update_no_size_hint')),
        updateFailedChecksTitle: @json(getTranslateByKey('update_failed_checks_title')),
        updateWarningChecksTitle: @json(getTranslateByKey('update_warning_checks_title')),
        updatePostActionsTitle: @json(getTranslateByKey('update_post_actions_title')),
        updateResultTitle: @json(getTranslateByKey('update_result_title')),
        updateSnapshotFile: @json(getTranslateByKey('update_snapshot_file')),
        updateBackupFile: @json(getTranslateByKey('update_backup_file')),
        updateDeployPath: @json(getTranslateByKey('update_deploy_path')),
        updateReloadNotice: @json(getTranslateByKey('update_reload_notice')),
        updateVersionCurrent: @json(getTranslateByKey('current_version')),
        updateVersionNext: @json(getTranslateByKey('next_version')),
        viewAsyncTasks: @json(getTranslateByKey('view_async_tasks')),
        asyncTaskListUrl: @json($adminAsyncTaskListUrl),
        asyncTaskUnavailable: @json('当前未安装日志模块或日志插件，暂时无法查看异步任务详情')
    };
    var updateIntervalId = null;
    var upgradeArtifacts = {};

    function clearUpdateInterval() {
        if (updateIntervalId) {
            clearInterval(updateIntervalId);
            updateIntervalId = null;
        }
    }

    function resetUpgradeArtifacts() {
        upgradeArtifacts = {};
    }

    function rememberUpgradeArtifacts(res) {
        if (!res) {
            return;
        }
        var data = res.data && typeof res.data === 'object' ? res.data : {};
        var nextArtifacts = $.extend({}, upgradeArtifacts);
        var isSnapshotResponse = res.stage === 'snapshot' || (res.target === 'cms' && res.stage === 'snapshot');
        if (isSnapshotResponse) {
            nextArtifacts.snapshot_file = res.snapshot_file || data.snapshot_file || data.file || nextArtifacts.snapshot_file || '';
            nextArtifacts.snapshot_size = res.snapshot_size || data.snapshot_size || data.size || nextArtifacts.snapshot_size || 0;
        }
        if (data.snapshot_file || res.snapshot_file) {
            nextArtifacts.snapshot_file = res.snapshot_file || data.snapshot_file || nextArtifacts.snapshot_file || '';
            nextArtifacts.snapshot_size = res.snapshot_size || data.snapshot_size || nextArtifacts.snapshot_size || 0;
        }
        if (data.file) {
            nextArtifacts.backup_file = data.file;
        }
        if (data.size) {
            nextArtifacts.backup_size = data.size;
        }
        upgradeArtifacts = nextArtifacts;
    }

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function formatFileSize(size) {
        var num = parseInt(size, 10) || 0;
        if (num <= 0) {
            return '0 B';
        }
        var units = ['B', 'KB', 'MB', 'GB'];
        var index = 0;
        while (num >= 1024 && index < units.length - 1) {
            num = num / 1024;
            index++;
        }
        return (index === 0 ? num : num.toFixed(num >= 100 ? 0 : 1)) + ' ' + units[index];
    }

    function basename(path) {
        var normalized = String(path || '').replace(/\\/g, '/');
        var parts = normalized.split('/');
        return parts.length ? parts[parts.length - 1] : normalized;
    }

    function getResponseMessage(res) {
        return res && res.msg ? res.msg : adminCmsLang.networkError;
    }

    function buildPrepareMeta(res) {
        var html = [];
        if (res && res.to_version) {
            html.push(adminCmsLang.updateTargetVersion + '：' + escapeHtml(res.to_version));
        }
        if (res && parseInt(res.file_size, 10) > 0) {
            html.push(adminCmsLang.updatePackageSize + '：' + formatFileSize(res.file_size));
        } else {
            html.push(escapeHtml(adminCmsLang.updateNoSizeHint));
        }
        if (res && res.warning_checks && res.warning_checks.length) {
            html.push('<strong>' + escapeHtml(adminCmsLang.updateWarningChecksTitle) + '</strong>');
            $.each(res.warning_checks, function (_, item) {
                html.push('- ' + escapeHtml(item.label || item.key || item.msg || ''));
            });
        }
        return html.join('<br>');
    }

    function buildDetailHtml(res) {
        var html = [];
        if (res && res.failed_checks && res.failed_checks.length) {
            html.push('<div style="margin-bottom:8px;"><strong>' + escapeHtml(adminCmsLang.updateFailedChecksTitle) + '</strong></div>');
            html.push('<ul style="padding-left:18px;margin:0 0 12px;">');
            $.each(res.failed_checks, function (_, item) {
                html.push('<li style="margin-bottom:6px;">' + escapeHtml(item.label || item.key || item.msg || '') + '</li>');
            });
            html.push('</ul>');
        }
        if (res && res.warning_checks && res.warning_checks.length) {
            html.push('<div style="margin-bottom:8px;"><strong>' + escapeHtml(adminCmsLang.updateWarningChecksTitle) + '</strong></div>');
            html.push('<ul style="padding-left:18px;margin:0 0 12px;">');
            $.each(res.warning_checks, function (_, item) {
                html.push('<li style="margin-bottom:6px;">' + escapeHtml(item.label || item.key || item.msg || '') + '</li>');
            });
            html.push('</ul>');
        }
        if (res && res.post_actions && res.post_actions.length) {
            html.push('<div style="margin-bottom:8px;"><strong>' + escapeHtml(adminCmsLang.updatePostActionsTitle) + '</strong></div>');
            html.push('<ul style="padding-left:18px;margin:0;">');
            $.each(res.post_actions, function (_, item) {
                var line = item && item.msg ? item.msg : '';
                if (item && item.status && item.status !== 'success') {
                    line += ' [' + item.status + ']';
                }
                html.push('<li style="margin-bottom:6px;">' + escapeHtml(line) + '</li>');
            });
            html.push('</ul>');
        }
        return html.join('');
    }

    function renderArtifactLine(label, file, size) {
        if (!file) {
            return '';
        }
        var text = escapeHtml(basename(file));
        if (parseInt(size, 10) > 0) {
            text += ' <span class="text-muted">(' + escapeHtml(formatFileSize(size)) + ')</span>';
        }
        return '<div style="margin-bottom:8px;"><strong>' + escapeHtml(label) + '</strong>：' + text + '</div>';
    }

    function buildAsyncTaskUrl(asyncId) {
        if (!adminCmsLang.asyncTaskListUrl) {
            return '';
        }
        return adminCmsLang.asyncTaskListUrl + '?async_id=' + encodeURIComponent(asyncId || '');
    }

    function buildPostActionItem(item) {
        var text = item && item.msg ? item.msg : '';
        var html = '<li style="margin-bottom:6px;">' + escapeHtml(text);
        var taskUrl = item && item.async_id ? buildAsyncTaskUrl(item.async_id) : '';
        if (taskUrl) {
            html += ' <a href="' + escapeHtml(taskUrl) + '" target="_blank">' + escapeHtml(adminCmsLang.viewAsyncTasks) + '</a>';
        }
        html += '</li>';
        return html;
    }

    function buildScannedPackageItem(item) {
        var text = item && item.label ? item.label : '';
        if (!text && item && item.name) {
            text = (item.type === 'plugin' ? '插件 ' : '模块 ') + item.name;
        }
        return '<li style="margin-bottom:6px;">' + escapeHtml(text) + '</li>';
    }

    function buildSuccessDetailHtml(res) {
        var html = [];
        var snapshotFile = (res && res.snapshot_file) || upgradeArtifacts.snapshot_file || '';
        var snapshotSize = (res && res.snapshot_size) || upgradeArtifacts.snapshot_size || 0;
        var backupFile = (res && res.backup_file) || upgradeArtifacts.backup_file || '';
        var backupSize = (res && res.backup_size) || upgradeArtifacts.backup_size || 0;

        html.push('<div style="margin-bottom:12px;line-height:1.8;">' + escapeHtml(getResponseMessage(res)) + '</div>');
        if (res && res.to_version) {
            html.push('<div style="margin-bottom:8px;"><strong>' + escapeHtml(adminCmsLang.updateTargetVersion) + '</strong>：' + escapeHtml(res.to_version) + '</div>');
        }
        html.push(renderArtifactLine(adminCmsLang.updateSnapshotFile, snapshotFile, snapshotSize));
        html.push(renderArtifactLine(adminCmsLang.updateBackupFile, backupFile, backupSize));
        if (res && res.package_path && res.package_path.resolved_path) {
            html.push('<div style="margin-bottom:8px;"><strong>' + escapeHtml(adminCmsLang.updateDeployPath) + '</strong>：' + escapeHtml(res.package_path.resolved_path) + '</div>');
        }
        if (res && res.scanned_packages && res.scanned_packages.length) {
            html.push('<div style="margin:12px 0 8px;"><strong>已扫描到的模块/插件迁移</strong></div>');
            html.push('<ul style="padding-left:18px;margin:0 0 12px;">');
            $.each(res.scanned_packages, function (_, item) {
                html.push(buildScannedPackageItem(item));
            });
            html.push('</ul>');
        }
        if (res && res.post_actions && res.post_actions.length) {
            html.push('<div style="margin:12px 0 8px;"><strong>' + escapeHtml(adminCmsLang.updatePostActionsTitle) + '</strong></div>');
            html.push('<ul style="padding-left:18px;margin:0 0 12px;">');
            $.each(res.post_actions, function (_, item) {
                html.push(buildPostActionItem(item));
            });
            html.push('</ul>');
        }
        html.push('<div class="text-muted" style="margin-top:12px;line-height:1.8;">' + escapeHtml(adminCmsLang.updateReloadNotice) + '</div>');
        return html.join('');
    }

    function showUpdateSuccess(res) {
        var detailHtml = buildSuccessDetailHtml(res);
        layer.alert('<div style="line-height:1.8;">' + detailHtml + '</div>', {
            title: adminCmsLang.updateResultTitle || adminCmsLang.updateStageFinish,
            area: ['560px', 'auto']
        }, function (index) {
            layer.close(index);
            location.reload();
        });
    }

    function showResponseError(res) {
        var detailHtml = buildDetailHtml(res);
        if (detailHtml) {
            layer.alert('<div style="line-height:1.8;">' + escapeHtml(getResponseMessage(res)) + '<div style="margin-top:12px;">' + detailHtml + '</div></div>', {
                title: adminCmsLang.commonTip,
                area: ['520px', 'auto']
            });
            return;
        }
        popup({type: "error", msg: getResponseMessage(res), delay: 2500});
    }

    function openUpdateProgressModal() {
        layer.open({
            type: 1,
            skin: 'layui-layer-demo',
            closeBtn: 1,
            anim: 2,
            shadeClose: false,
            content: '<div class="col-md-12">\n' +
                '    <div class="card">\n' +
                '        <div class="card-header card-default">' + escapeHtml(adminCmsLang.downloadLatestVersionLoading) + (adminCmsLang.asyncTaskListUrl ? '<a href="' + adminCmsLang.asyncTaskListUrl + '" target="_blank" style="float:right;font-size:12px;">' + escapeHtml(adminCmsLang.viewAsyncTasks) + '</a>' : '<span style="float:right;font-size:12px;color:#999;">' + escapeHtml(adminCmsLang.asyncTaskUnavailable) + '</span>') + '</div>\n' +
                '        <div class="card-body">\n' +
                '            <div class="progress-info text-muted" style="margin-bottom:10px;">' + escapeHtml(adminCmsLang.updateCurrentStage) + ' <span class="float-right" id="update-stage-label">' + escapeHtml(adminCmsLang.updatePreparing) + '</span></div>\n' +
                '            <div id="update-stage-meta" class="text-muted small" style="line-height:1.8;margin-bottom:12px;"></div>\n' +
                '            <div class="progress-info text-muted">' + @json(getTranslateByKey('progress_complete')) + ' <span class="float-right" id="progress-info">0%</span></div>\n' +
                '            <div class="progress">\n' +
                '                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>\n' +
                '            </div>\n' +
                '        </div>\n' +
                '    </div>\n' +
                '</div>'
        });
    }

    function setUpdateStage(label, metaHtml) {
        $('#update-stage-label').html(escapeHtml(label || ''));
        $('#update-stage-meta').html(metaHtml || '');
    }

    function setUpdateProgress(progress) {
        var safeProgress = Math.max(0, Math.min(100, progress || 0));
        var text = safeProgress.toFixed(1).replace(/\.0$/, '') + '%';
        $('#progress-info').html(text);
        $('.progress-bar').css('width', text).attr('aria-valuenow', safeProgress).html(text);
    }

    // Clear cache
    function clearCache() {

        var index = layer.load(1, {
            shade: [0.5, '#000'], // translucent white background
            content: adminCmsLang.clearCacheLoading,
            success: function (layero) {
                layero.find('.layui-layer-content').css({
                    'padding-top': '39px',
                    'width': '100px',
                    "color": "#FFF",
                    "background-position": "center center",
                    "text-align": "center",
                });
            }
        });

        $.ajax({
            "method": "post",
            "url": "{{url('admin/clear')}}",
            "timeout": 0,
            "dataType": 'json',
            "data": {"_token": "{{csrf_token()}}"},
            "success": function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    popup({
                        type: "success", msg: res.msg, delay: 2000, callBack: function () {
                            window.location.reload();
                        }
                    });
                } else {
                    popup({
                        type: "error", msg: res.msg, delay: 2000, callBack: function () {
                            window.location.reload();
                        }
                    });
                }
            },
            "error": function (res) {
                console.log(res);
            }
        })
    }
    // Update CMS
    function cmsUpdateVersion() {
        $.confirm({
            title: adminCmsLang.commonTip,
            content: '{{getTranslateByKey("common_sure_to_update_cms")}}',
            type: 'default',
            buttons: {
                ok: {
                    text: adminCmsLang.commonEnsure,
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function () {
                        download();
                    }
                },
                cancel: {
                    text: adminCmsLang.commonCancel
                }
            }
        });
    }
    // Start download
    function download() {
        resetUpgradeArtifacts();

        var index = layer.load(1, {
            shade: [0.6, '#000'], // translucent white background
            content: '<span class="layer-span">' + adminCmsLang.backupVersionLoading + '</span>',
            success: function (layero) {
                layero.find('.layui-layer-content').css({
                    'padding-top': '39px',
                    'width': 'auto',
                    "color": "#FFF",
                });
                //layer-span
                layero.find('.layer-span').css({
                    "margin-left": "-18px",
                });

            }
        });

        createUpgradeSnapshot().always(function () {
            bakFiles();
        }).done(function (res) {
            rememberUpgradeArtifacts(res);
        });
    }

    function createUpgradeSnapshot() {
        var params = '?identification=cms&action=snapshot';
        return $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{url('admin/cms/updateCmsVersion')}}" + params,
            "timeout": 0,
            "dataType": 'json'
        }).done(function (res) {
            rememberUpgradeArtifacts(res);
            if (res && res.status === 200 && res.snapshot_file) {
                popup({type: "success", msg: '升级快照已生成：' + res.snapshot_file, delay: 1800});
            }
        });
    }
    function checkcms(){

        var params = '?identification=cms&action=check';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{url('admin/cms/updateCmsVersion')}}" + params,
            "timeout": 0,
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (res) {
                if (res.status == 200) {
                    $('.updateCMS').css('display', 'block');
                    $('.hiden').css('visibility', 'visible');

                } else {

                }
            },
            "error": function (res) {
                console.log(res);
            }
        });
    }
    // Backup
    function bakFiles() {
        var params = '?identification=cms&action=backup';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{url('admin/cms/updateCmsVersion')}}" + params,
            "timeout": 0,
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (res) {
                if (res.status == 200) {
                    rememberUpgradeArtifacts(res);
                    layer.closeAll();
                    popup({type: "success", msg: res.msg, delay: 2000});
                    setTimeout(function () {
                        update("cms","cms");
                    }, 2000);

                } else {
                    popup({type: "error", msg: res.msg, delay: 2000});
                }
            },
            "error": function (res) {
                console.log(res);
            }
        });

    }
    function buildUpdateRequestUrl(identification, cloudtype, action) {
        return "{{url('admin/cms/updateCmsVersion')}}" + '?identification=' + identification + '&action=' + action + '&cloudtype=' + cloudtype;
    }

    function runUpdateProcess(identification,cloudtype) {
        if (cloudtype !== 'cms') {
            resetUpgradeArtifacts();
        }
        clearUpdateInterval();
        openUpdateProgressModal();
        setUpdateStage(adminCmsLang.updateStagePrepare, '');
        setUpdateProgress(0);

        var file_size = 0;
        var progress = 0;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": buildUpdateRequestUrl(identification, cloudtype, 'prepare-download'),
            "timeout": 0,
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
        })
            .done(function (json) {
                if (json.status !== 200) {
                    layer.closeAll();
                    showResponseError(json);
                    return;
                }
                file_size = json.file_size;
                setUpdateStage(adminCmsLang.updateStageDownload, buildPrepareMeta(json));

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    "method": "post",
                    "url": buildUpdateRequestUrl(identification, cloudtype, 'start-download'),
                    "timeout": 0,
                    "dataType": 'json',
                    "cache": false,
                    "processData": false,
                    "contentType": false,
                })
                    .done(function (json) {
                        if(json.status == 200){
                            setTimeout(function () {
                                progress = 100;
                                setUpdateProgress(progress);
                                Finished(identification,cloudtype);
                            }, 1000);
                        }else {
                            clearUpdateInterval();
                            layer.closeAll();
                            showResponseError(json);
                        }
                        console.log("Downloading finished");
                        console.log(json);
                    })
                    .fail(showAjaxError);

                updateIntervalId = window.setInterval(function () {

                    setUpdateProgress(progress);

                    if (progress >= 100) {
                    } else {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            "method": "post",
                            "url": buildUpdateRequestUrl(identification, cloudtype, 'get-file-size'),
                            "timeout": 0,
                            "dataType": 'json',
                            "cache": false,
                            "processData": false,
                            "contentType": false,
                        })
                            .done(function (json) {
                                if (json.status !== 200) {
                                    clearUpdateInterval();
                                    layer.closeAll();
                                    showResponseError(json);
                                    return;
                                }
                                if (file_size > 0) {
                                    progress = parseInt((json.size / file_size) * 1000) / 10;
                                    if (progress > 99.9) {
                                        progress = 99.9;
                                    }
                                }

                                console.log("Progress: " + progress);
                            })
                            .fail(showAjaxError);
                    }

                }, 1000);

            })
            .fail(showAjaxError);

    }
    function bindCloudLicenseAndContinue(identification, cloudtype) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": buildUpdateRequestUrl(identification, cloudtype, 'bind-license-site'),
            "timeout": 0,
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
        })
            .done(function (res) {
                if (res.status === 200) {
                    popup({type: "success", msg: res.msg || '站点绑定成功，开始下载', delay: 1500});
                    update(identification, cloudtype);
                    return;
                }
                showResponseError(res);
            })
            .fail(showAjaxError);
    }
    function showLicenseBlockedDialog(identification, cloudtype, res) {
        var buyUrl = res.buy_url || (res.actions && res.actions.buy_url) || '';
        var bindUrl = res.bind_url || (res.actions && res.actions.bind_url) || '';
        var licenseStatus = res.license_status || '';
        var buttons = {
            cancel: {
                text: adminCmsLang.commonCancel
            }
        };
        if (licenseStatus === 'paid_unbound') {
            buttons.ok = {
                text: '绑定当前站点',
                btnClass: 'btn-primary',
                action: function () {
                    bindCloudLicenseAndContinue(identification, cloudtype);
                    return false;
                }
            };
        } else if (buyUrl) {
            buttons.ok = {
                text: '前往查看',
                btnClass: 'btn-primary',
                action: function () {
                    window.open(buyUrl, '_blank');
                }
            };
        } else if (bindUrl) {
            buttons.ok = {
                text: '开发者中心',
                btnClass: 'btn-primary',
                action: function () {
                    window.open(bindUrl, '_blank');
                }
            };
        }

        $.confirm({
            title: '下载提示',
            content: res.msg || '当前资源暂时无法直接下载',
            type: 'orange',
            buttons: buttons
        });
    }
    function guardCloudInstallAuthorization(identification, cloudtype, onAuthorized) {
        if (cloudtype === 'cms') {
            onAuthorized();
            return;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": buildUpdateRequestUrl(identification, cloudtype, 'license-check'),
            "timeout": 0,
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
        })
            .done(function (res) {
                if (res.status !== 200) {
                    showResponseError(res);
                    return;
                }

                if (res.sale_type === 'free' || res.can_download || res.license_status === 'paid_authorized') {
                    onAuthorized();
                    return;
                }

                showLicenseBlockedDialog(identification, cloudtype, res);
            })
            .fail(showAjaxError);
    }
    // Upgrade package
    function update(identification,cloudtype) {
        guardCloudInstallAuthorization(identification, cloudtype, function () {
            runUpdateProcess(identification, cloudtype);
        });
    }
    function Finished(identification,cloudtype) {
        clearUpdateInterval();
        setUpdateStage(adminCmsLang.updateStageUnzip, '');
        setUpdateProgress(100);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": buildUpdateRequestUrl(identification, cloudtype, 'unzip-file'),
            "timeout": 0,
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
        })
            .done(function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    rememberUpgradeArtifacts(res);
                    showUpdateSuccess(res);
                } else {
                    showResponseError(res);
                }

            })
            .fail(showAjaxError)
    }
    function showAjaxError(e) {
        layer.closeAll();
        clearUpdateInterval();
        layer.alert(adminCmsLang.networkError, {
            icon: 2,
            skin: 'layer-ext-moon'
        })
    }
    function getsdks() {
        var params = '?identification=cms&action=get-sdks';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{url('admin/cms/updateCmsVersion')}}" + params,
            "timeout": 0,
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (res) {
                if (res.status == 200) {
                    var data = res.data.list.data;
                    var html = '';
                    for (var i = 0; i < data.length; i++) {
                        html += "<span >"+ data[i].name + " <a href='" + data[i].url + "' target='_blank'>" + data[i].url + "</a></span> &nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                    $('#sdks').html(html);
                } else {
                }
            },
            "error": function (res) {
                console.log(res);
            }
        });
    }

    function checkModuleVersion(identification,cloudtype,version){
        var params = '?identification='+identification+'&action=check&cloudtype='+cloudtype+'&version='+version;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{url('admin/cms/updateCmsVersion')}}" + params,
            "timeout": 0,
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (res) {
                if (res.status == 200) {
                    $('.module-update-'+identification).css('visibility', 'visible');
                } else {

                }
            },
            "error": function (res) {
                console.log(res);
            }
        });
    }

    function updateVersion(identification,cloudtype,reason){
        if (reason) {
            popup({type: "error", msg: reason, delay: 2000});
            return;
        }
        $.confirm({
            title: adminCmsLang.commonTip,
            content: adminCmsLang.sureToUpdateVersion,
            type: 'default',
            buttons: {
                ok: {
                    text: adminCmsLang.commonEnsure,
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function () {
                        update(identification,cloudtype);
                    }
                },
                cancel: {
                    text: adminCmsLang.commonCancel
                }
            }
        });
    }

    function privatization(identification,cloudtype) {
        layer.open({
            type: 1,
            title: adminCmsLang.commonTip,
            // skin: 'layui-layer-demo', // style class
            closeBtn: 1, // hide close button
            anim: 2,
            shadeClose: true, // allow close on shade click
            area:['auto','auto'],
            content: $(`.privatization_${cloudtype}_${identification}`),
        });
    }
</script>
