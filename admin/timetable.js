$(document).ready(function () {
  $("#calendar").fullCalendar({
    defaultView: "agendaWeek",
    minTime: "07:30:00",
    maxTime: "14:00:00",
    slotDuration: "00:30:00",
    allDaySlot: false,
    header: {
      left: "prev,next today",
      center: "title",
      right: "month,agendaWeek,agendaDay",
    },
    editable: true,
    selectable: true,
    selectHelper: true,
    select: function (start, end) {
      var title = prompt("Enter Event Title:");
      var classID = prompt("Enter Class ID:");
      var subjectID = prompt("Enter Subject ID:");
      if (title && classID && subjectID) {
        var formattedStart = moment(start).format("YYYY-MM-DD HH:mm:ss");
        var formattedEnd = moment(end).format("YYYY-MM-DD HH:mm:ss");
        console.log(
          "Adding Event:",
          title,
          classID,
          subjectID,
          formattedStart,
          formattedEnd
        );
        $.ajax({
          url: "add_event.php",
          type: "POST",
          data: {
            title: title,
            start: formattedStart,
            end: formattedEnd,
            classID: classID,
            subjectID: subjectID,
          },
          success: function (response) {
            console.log("Server Response:", response);
            $("#calendar").fullCalendar("refetchEvents");
            alert("Added Successfully");
          },
          error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
          },
        });
      } else {
        alert("All fields are required!");
      }
    },
    eventDrop: function (event, delta, revertFunc) {
      alert("Dropped on " + event.start.format() + " to " + event.end.format());
      var start = event.start.format("YYYY-MM-DD HH:mm:ss");
      var end = event.end.format("YYYY-MM-DD HH:mm:ss");
      console.log("Updating Event:", event.title, event.id, start, end);
      $.ajax({
        url: "update_event.php",
        type: "POST",
        data: { id: event.id, title: event.title, start: start, end: end },
        success: function (response) {
          console.log("Update Response:", response);
          $("#calendar").fullCalendar("refetchEvents");
          alert("Event Updated");
        },
        error: function (xhr, status, error) {
          console.error("Update AJAX Error:", status, error);
          revertFunc();
        },
      });
    },
  });
});
