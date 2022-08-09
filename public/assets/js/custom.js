function toastrSuccess(message, title = null) {
    $.NotificationApp.send(title, message, "top-right", "rgba(0,0,0,0.2)", "success", 3e3, 1)
}

function toastrError(message, title = null) {
    $.NotificationApp.send(title, message, "top-right", "rgba(0,0,0,0.2)", "error", 3e3, 1)
}

function toastrInfo(message, title = null) {
    $.NotificationApp.send(title, message, "top-right", "rgba(0,0,0,0.2)", "info", 3e3, 1)
}

function toastrWarning(message, title = null) {
    $.NotificationApp.send(title, message, "top-right", "rgba(0,0,0,0.2)", "warning", 3e3, 1)
}

function goBack() {
    history.back()
}

//Open Modal
function openModal(modalName, modalTitle, modalForm, modalTitleName, id = 0, flag = 0) {
    $(modalTitleName).text(modalTitle);
    $(modalForm).parsley().reset();
    $(modalName).modal('show');
    if (flag == 1) {
        $("#image_one").val(null);
        $("#image_two").val(null);
        $("#image_three").val(null);
    }

    if (flag == 2) {
        $('#image_one, #image_two, #image_three').prop('required', true);
    }

    if (flag == 3) {
        $('#city_id').html('<option value="">Choose</option>');
        $('#state_id').html('<option value="">Choose</option>');
        getStatesList(101);
    }

    if (flag == 4) {
        $("#sale_price").prop('readonly', true);
        $("#cost_price").prop('readonly', true);
        $(".tax-div").hide();
    }
    resetForm(modalForm)
    $('#id').val(id);
}

$('input[name="next_follow_up"]').click(function () {
    if ($(this).val() == 'Yes') {
        $(".follow-up-div").show();
        $("#followup_time").prop('required', true);
        $("#followup_date").prop('required', true);
    } else {
        $(".follow-up-div").hide();
        $("#followup_time").prop('required', false);
        $("#followup_date").prop('required', false);
    }
});

function openFollowUpModal(modalName, modalTitle, modalForm, modalTitleName, estimate_id = 0, id = 0, estimate_no, estimate_status, url, est_type = 0, customer_name, mobile_no) {
    let flg = 'denc';
    if (est_type) {
        flg = 'enc';
    }
    $("#event_id").val(0);
    follow_up_list(estimate_id, url, flg)
    $(modalTitleName).text(modalTitle);
    $(modalForm).parsley().reset();
    $(modalName).modal('show');
    resetForm(modalForm)
    $('#id').val(id);
    $('.estimate_customer_span').html(customer_name + "/" + mobile_no);
    $('.estimate_span').html('#' + estimate_no);
    $('#estimate_id').val(estimate_id);
    // $('#event_id').val(event_id);
    $('#estimate_status').val(estimate_status);
    $(".follow-up-div").show();
    $("#followup_time").prop('required', true);
    $("#followup_date").prop('required', true);
}

//Reset form validation
function resetForm(formName) {
    $(formName)[0].reset();
    $('#id').val('0');
}

//Form validation
function formValition(e) {
    $(e).parsley();
}

function resetFormValidation(formName) {
    $(formName).parsley().reset();
}

function remove_id(id, url, tableName) {
    if (id.length <= 0) {
        toastrWarning('Please select at least one record', 'Warning');
    } else {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085D6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: !1,
            preConfirm: function () {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {id: id},
                    dataType: "json",
                    success: function (data, textStatus, jqXHR) {
                        $(tableName).DataTable().ajax.reload();
                        $("#remove_" + id).closest("tr").hide('slow');
                        $('#select_all').prop('checked', false);
                        $("#select_count").html(0);
                        // toastrSuccess('Successfully removed');
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText
                        switch (xhr.status) {
                            case 401:
                                toastrError('Error in saving...', 'Error');
                                break;
                            case 422:
                                toastrInfo('Please contact developer.', 'Info');
                                break;
                            case 409:
                                toastrInfo('Name already exist.', 'Warning');
                                break;
                            default:
                                toastrError('Error - ' + errorMessage, 'Error');
                        }
                    },
                    complete: function (data) {
                    }
                });
            }
        }).then(function (t) {
            t.value && Swal.fire({
                title: "Success",
                text: "Your record has been updated.",
                type: "success", showConfirmButton: !1,
                timer: 1500,
                confirmButtonClass: "btn btn-success",
                showConfirmButton: false
            })
        });
    }
}

function change_status(id, status, url, tableName, flag = 0, old_value = null) {
    var tmp_status = 'deactive';
    if (status == 0) {
        tmp_status = 'active';
    }

    if (flag)
        tmp_status = status;
    if (id.length <= 0) {
        toastrWarning('Please select at least one record', 'Warning');
    } else {
        Swal.fire({
            title: "Are you sure?",
            // text: "You won't be able to revert this!",
            text: "Are you sure want to " + tmp_status,
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085D6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, change it!",
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: !1,
            preConfirm: function () {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {id: id, status: status},
                    dataType: "json",
                    success: function (data, textStatus, jqXHR) {
                        // toastrSuccess('Successfully updated');
                        $(tableName).DataTable().ajax.reload();
                        $('#select_all').prop('checked', false);
                        $("#select_count").html(0);
                    }
                });
            }
        }).then(function (t) {
            if (t.isConfirmed)
                t.value && Swal.fire({
                    title: "Success",
                    text: "Your record has been updated.",
                    type: "success",
                    showConfirmButton: !1,
                    timer: 1500,
                    confirmButtonClass: "btn btn-success",
                    showConfirmButton: false
                })
            else
                var oldValueArr = old_value.split('_');
            $('#example-select_' + oldValueArr[1]).val(oldValueArr[0]);
        });
    }
}

function fn_follow_up_history(url) {
    localStorage.setItem('start', moment().subtract(89, 'days'));
    localStorage.setItem('end', moment().subtract(1, 'days'));
    window.location.href = url;
}

$(document).on('click', '#select_all', function () {
    $(".single_checkbox").prop("checked", this.checked);
    $("#select_count").html($("input.single_checkbox:checked").length);
});

$(document).on('click', '.single_checkbox', function () {
    if ($('.single_checkbox:checked').length == $('.single_checkbox').length) {
        $('#select_all').prop('checked', true);
    } else {
        $('#select_all').prop('checked', false);
    }
    $("#select_count").html($("input.single_checkbox:checked").length);
});

function getDateWiseFollowUpList(url, date = null, follow_up_url) {

    $.ajax({
        async: false,
        type: "GET",
        url: url,
        data: {date: date},
        dataType: "json",
        beforeSend: function () {
            $('.loader').show();
        },

        success: function (res) {
            var tmpHtml = '';
            $.each(res.data, function (i, currProgram) {
                tmpHtml += '<h5 class="m-0 pb-2 fs-4">' +
                    ' <span class="badge badge-info-lighten p-1">' + i + '</span>' +
                    '</h5>';
                $.each(currProgram, function (key, val) {
                    tmpStr = '<span class="badge badge-outline-' + val.color + '">Take Follow up on :' + moment(val.start_date, 'DD-MM-YYYY HH:mm:ss').format("DD MMMM, YYYY HH:mm A") + '</span>';
                    tmpStatus = '<li class="list-inline-item"><i class="mdi mdi-circle-small"></i></li>' +
                        '<li class="list-inline-item"><span class="badge badge-outline-dark"><i class="mdi mdi-check-decagram"></i> ' + val.status + '</span></li>';
                    htmlAction = "openFollowUpModal('#follow-up-modal','Next Follow Up','#follow-up-form','.modal-title'," + val.estimate_id + "," + val.id + ",'" + val.estimate_no + "','" + val.status + "','" + follow_up_url + "',0,'" + val.customer_name + "','" + val.mobile_no + "')";

                    if (val.next_follow_up == 1) {
                        tmpStr = '<span class="badge badge-outline-primary"><i class="mdi mdi-close-box"></i> ' + val.status + '</span>';
                        tmpStatus = '';
                        htmlAction ='';
                    }
                    var tmpNotes = val.notes;
                    if (val.notes.length > 50) {
                        tmpNotes = val.notes.slice(0, 50) + "...";
                    }

                     tmpHtml += '<div class="inbox-item border rounded p-2 mb-2">' +
                        // ' <div class="inbox-item-img"><i class="mdi mdi-clock-alert widget-icon rounded-circle bg-secondary-lighten text-secondary"></i></div>'+
                        '<p class="inbox-item-author"><a href="javascript:void(0);"onclick="' + htmlAction + '" class="text-dark"><b>#' + val.estimate_no + ' (' + val.customer_name + ' - ' + val.mobile_no + ')</b></a></p>' +
                        '<p class="inbox-item-text">' + tmpNotes + '</p>' +
                        // '<p class="inbox-item-text">' +
                        '<ul class="inbox-item-text" style="padding-left:0px !important;">' +
                        '<li class="list-inline-item badge badge-secondary-lighten">' + moment(val.created_datetime, 'DD-MM-YYYY HH:mm:ss').format("DD MMMM, YYYY HH:mm A") + '</li>' +
                        '<li class="list-inline-item"><i class="mdi mdi-circle-small"></i></li>' +
                        '<li class="list-inline-item">' + tmpStr + '</li>' +
                        tmpStatus +
                         '<li class="list-inline-item"><i class="mdi mdi-circle-small"></i></li>' +
                         '<li class="list-inline-item badge badge-dark-lighten"><small>Created by - ' + val.user_name + '</small></li>' +
                        '</ul>' +
                        // '</p>' +
                        ' <p class="inbox-item-date">' +
                        ' <a href="javascript:void(0);" onclick="' + htmlAction + '" class="text-dark"> <i class="uil uil-stopwatch font-22"></i> </a>' +
                        '</p>' +
                        '</div>';
                });
            });
            $('.follow-up-list').html(tmpHtml);

        },
        error: function (xhr, status, error) {
            var status = JSON.parse(xhr.responseText);
            $(".follow-up-list").html('<div class="align-items-center text-center"><h4>' + status.success + '</h4></div>');
            $('.loader').show();
        },
        complete: function (data) {
            $('.loader').hide();
        }

    });
}

function follow_up_list(val, url, flg) {
    $.ajax({
        async: false,
        type: "GET",
        url: url,
        data: {val: val, flg: flg},
        dataType: "json",
        beforeSend: function () {
            $(".timeline-list").html('<div class="text-center">\n' +
                '<i class="mdi mdi-dots-circle mdi-spin font-20 text-muted"></i>\n' +
                '</div>');
        },
        success: function (res) {
            var tmpHtml = '';

            $.each(res.data, function (key, val) {
                if (key == 0) {
                    $("#follow-up-modal #event_id").val(val.id);
                }
                var tmpStr = '';
                if (val.next_follow_up == 1)
                    tmpStr = '<li class="list-inline-item"><i class="mdi mdi-circle-small"></i></li> <span class="badge badge-outline-primary"><i class="mdi mdi-close-box"></i> ' + val.status + '</span>';
                tmpHtml += '<div class="border rounded p-2 mb-2">' +
                    '<p class="text-muted mb-0">' + val.notes + '</p>' +

                    '<ul class="inbox-item-text" style="padding-left:0px !important;margin-bottom:0px !important;">' +
                    '<li class="list-inline-item badge badge-secondary-lighten">' + val.created_at + '</li>' +
                    '<li class="list-inline-item"><i class="mdi mdi-circle-small"></i></li>' +
                    '<li class="list-inline-item badge badge-secondary-lighten">Take Follow up on :' + val.start_date + '</li>' +
                    '<li class="list-inline-item">' + tmpStr + '</li>' +
                    '<li class="list-inline-item"><i class="mdi mdi-circle-small"></i></li>' +
                    '<li class="list-inline-item badge badge-dark-lighten"><small>Created by - ' + val.user_name + '</small></li>' +
                    '</ul>' +
                    // '<p class="text-muted mb-0"><span class="badge badge-secondary-lighten">' + val.start_date + '</span>' + tmpStr + ' <span class="text-muted">' + val.user_name + '</span></p>' +
                    '</div>';
            });
            $('.timeline-list').html(tmpHtml);
        },
        error: function (xhr, status, error) {
            var status = JSON.parse(xhr.responseText);
            $(".timeline-list").html('<div class="align-items-center text-center"><h4>' + status.success + '</h4></div>');
        },
        complete: function (data) {

        }
    });
}

function salesPerformanceChart(date_range, url) {


    $.ajax({
        async: false,
        type: "GET",
        url: url,
        data: {date: date_range},
        dataType: "json",
        beforeSend: function () {
            $("#sales-performance-chart").html('<div class="text-center">' +
                '<i class="mdi mdi-dots-circle mdi-spin font-20 text-muted"></i>' +
                '</div>');
        },
        success: function (res) {
            $("#total_task_span").html(res.total_task);
            $("#completed_task_span").html(res.completed_task);
            ApexCharts.exec('mychart', 'updateOptions', {

                labels: res.labels,
                series: res.series,
                plotOptions: {
                    radialBar: {
                        dataLabels: {
                            total: {
                                show: true,
                                label: res.labels[1],
                                formatter: function (w) {
                                    // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                                    return res.series[1] + '%'
                                }
                            }
                        }
                    }
                }

            }, false, true);
        },
        error: function (xhr, status, error) {
            // var status = JSON.parse(xhr.responseText);
            // $(".timeline-list").html('<div class="align-items-center text-center"><h4>' + status.success + '</h4></div>');
        },
        complete: function (data) {

        }
    });
}

function barChart(date_range) {
    $.ajax({
        type: "GET",
        async: false,
        url: SITEURL + "/bar-chart",
        data: {date: date_range},
        dataType: "json",
        beforeSend: function () {
            $("#chart").html('<div class="text-center">' +
                '<i class="mdi mdi-dots-circle mdi-spin font-20 text-muted"></i>' +
                '</div>');
        },
        success: function (res) {
            ApexCharts.exec('bar_chart', 'updateOptions', {
                series: [{
                    name: 'Total',
                    data: res.sent
                }, {
                    name: 'Accepted',
                    data: res.close
                }],
                xaxis: {
                    categories: res.labels,
                },
            }, false, true);
            item = res;
        }
    });
}

function estimate_duplicate(id) {
    $.ajax({
        type: "POST",
        async: false,
        url: SITEURL + "/estimate-duplicate",
        data: {id: id},
        dataType: "json",
        // beforeSend: function () {
        //     $("#chart").html('<div class="text-center">' +
        //         '<i class="mdi mdi-dots-circle mdi-spin font-20 text-muted"></i>' +
        //         '</div>');
        // },
        success: function (res) {
            toastrSuccess('Estimate copied');
            $("#estimate-datatable").DataTable().ajax.reload();
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText
            switch (xhr.status) {
                case 401:
                    toastrError('Error in estimate coping...', 'Error');
                    break;
                case 422:
                    toastrInfo('Please contact developer.', 'Info');
                    break;
                case 409:
                    toastrInfo('Name already exist.', 'Warning');
                    break;
                default:
                    toastrError('Error - ' + errorMessage, 'Error');
            }
        },
    });
}
