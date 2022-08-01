!function (l) {
    "use strict";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function e() {
        this.$body = l("body"),
            this.$modal = new bootstrap.Modal(document.getElementById("event-modal"), {backdrop: "static"}),
            this.$calendar = l("#calendar"),
            this.$formEvent = l("#form-event"),
            this.$btnNewEvent = l("#btn-new-event"),
            this.$btnDeleteEvent = l("#btn-delete-event"),
            this.$btnSaveEvent = l("#btn-save-event"), this.$modalTitle = l("#modal-title"),
            this.$calendarObj = null, this.$selectedEvent = null, this.$newEventData = null
    }

    e.prototype.onEventClick = function (e) {

        this.$formEvent[0].reset(),
            this.$formEvent.removeClass("was-validated"),
            this.$newEventData = null,
            this.$btnDeleteEvent.show(),
            this.$modalTitle.text("Edit Event"),
            this.$modal.show(), this.$selectedEvent = e.event,
            l("#event-title").val(this.$selectedEvent.title.split(" - ")[0]),
            l("#event-category").val(this.$selectedEvent.classNames[0])
            l("#event-time").val(moment(this.$selectedEvent.start).format("HH:mm"));
            l("#event-id").val(this.$selectedEvent.id)

    }, e.prototype.onSelect = function (e) {
        $('#form-event #event-id').val(0);
        this.$formEvent[0].reset(),
            this.$formEvent.removeClass("was-validated"),
            this.$selectedEvent = null,
            this.$newEventData = e,
            this.$btnDeleteEvent.hide(),
            this.$modalTitle.text("Add New Event"),
            this.$modal.show(),
            this.$calendarObj.unselect()
    }, e.prototype.init = function () {
        var e = new Date(l.now());
        // new FullCalendar.Draggable(document.getElementById("external-events"), {
        //     itemSelector: ".external-event",
        //     eventData: function (e) {
        //         return {title: e.innerText, className: l(e).data("class")}
        //     }
        // });
        // var t = findItem(),
        var t = function () {
                var item = null;
                $.ajax({
                    type: "GET",
                    async: false,
                    url: SITEURL + "/calendar-event",
                    // data: {start: moment(new Date()).format("YYYY-MM-DD"), end: moment(new Date(new Date().setDate(new Date().getDate() + 30))).format("YYYY-MM-DD")},
                    data: {start: moment(new Date(new Date().setDate(new Date().getDate() - 30))).format("YYYY-MM-DD"), end: moment(new Date(new Date().setDate(new Date().getDate() + 30))).format("YYYY-MM-DD")},
                    dataType: "json",
                    success: function (res) {
                        item = res;
                    }
                });
                return item;
            }(),
            a = this;
        a.$calendarObj = new FullCalendar.Calendar(a.$calendar[0], {
            slotDuration: "00:15:00",
            slotMinTime: "08:00:00",
            slotMaxTime: "19:00:00",
            themeSystem: "bootstrap",
            bootstrapFontAwesome: !1,
            buttonText: {
                today: "Today",
                month: "Month",
                week: "Week",
                day: "Day",
                list: "List",
                prev: "Prev",
                next: "Next"
            },
            initialView: "dayGridMonth",
            handleWindowResize: !0,
            height: l(window).height() - 200,
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
            },
            disableDragging: true,
            editable: false,
            initialEvents: t,
            // editable: !0,
            droppable: !0,
            selectable: !0,
            dateClick: function (e) {
                a.onSelect(e)
            },
            eventClick: function (e) {
                a.onEventClick(e)
            }
        }), a.$calendarObj.render(), a.$btnNewEvent.on("click", function (e) {
            a.onSelect({date: new Date, allDay: !0})
        }), a.$formEvent.on("submit", function (e) {
            e.preventDefault();
            var t, n = a.$formEvent[0];

            if ($('#event-id').val() > 0) {
                var date = moment(a.$selectedEvent.start).format("YYYY/MM/DD");
                var url = SITEURL + "/calendar-event/update";
            } else {
                var url = SITEURL + "/calendar-event/create";
                var date = moment(a.$newEventData.date).format("YYYY/MM/DD");

            }
            var event_time = $("#event-time").val();
            var event_date='';

            $.ajax({
                type: "POST",
                url: url,
                async:false,
                data: {
                    title: l("#event-title").val(),
                    start: date,
                    event_time: event_time,
                    // allDay: a.$newEventData.allDay,
                    className: l("#event-category").val(),
                    id: l("#event-id").val()
                },
                dataType: "json",
                success: function (res) {
                    toastrSuccess('Event Saved!', 'Success');
                    // var ti = event_time;
                    if($('#event-id').val() > 0)
                        event_date = new Date(date + ' ' +event_time).toString();
                    else
                        event_date = a.$newEventData.date;


                }
            });

            n.checkValidity() ? (a.$selectedEvent ? (a.$selectedEvent.setProp("title", l("#event-title").val()),
                a.$selectedEvent.setProp("classNames", [l("#event-category").val()])) : (t = {
                title: l("#event-title").val(),
                start: event_date,
                // start: a.$newEventData.date,
                // allDay: a.$newEventData.allDay,
                className: l("#event-category").val()
            }, a.$calendarObj.addEvent(t)), a.$modal.hide()) : (e.stopPropagation(), n.classList.add("was-validated"))
        }), l(a.$btnDeleteEvent.on("click", function (e) {
            $.ajax({
                type: "POST",
                url: SITEURL + "/calendar-event/delete",
                data: {
                    id: l("#event-id").val()
                },
                dataType: "json",
                success: function (res) {
                    toastrSuccess('Event Deleted!', 'Success')
                    a.$selectedEvent && (a.$selectedEvent.remove(), a.$selectedEvent = null, a.$modal.hide())
                }
            });

        }))
    }, l.CalendarApp = new e, l.CalendarApp.Constructor = e
}(window.jQuery), function () {
    "use strict";
    window.jQuery.CalendarApp.init()
}();
