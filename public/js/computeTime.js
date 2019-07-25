function Converttimeformat(time1, time2) {
        // var time = $("#starttime").val();
        var time = document.getElementById(time1).value;
        var hrs = Number(time.match(/^(\d+)/)[1]);
        var mnts = Number(time.match(/:(\d+)/)[1]);
        var format = time.match(/\s(.*)$/)[1];
        if (format == "pm" && hrs < 12) hrs = hrs + 12;
        if (format == "am" && hrs == 12) hrs = hrs - 12;
        var hours =hrs.toString();
        var minutes = mnts.toString();
        if (hrs < 10) hours = "0" + hours;
        if (mnts < 10) minutes = "0" + minutes;
        //alert(hours + ":" + minutes);

        var date1 = new Date();
        date1.setHours(hours );
        date1.setMinutes(minutes);
        //alert(date1);

        var time = document.getElementById(time2).value;
        var hrs = Number(time.match(/^(\d+)/)[1]);
        var mnts = Number(time.match(/:(\d+)/)[1]);
        var format = time.match(/\s(.*)$/)[1];
        if (format == "pm" && hrs < 12) hrs = hrs + 12;
        if (format == "am" && hrs == 12) hrs = hrs - 12;
        var hours = hrs.toString();
        var minutes = mnts.toString();
        if (hrs < 10) hours = "0" + hours;
        if (mnts < 10) minutes = "0" + minutes;
        //alert(hours+ ":" + minutes);
        var date2 = new Date();
        date2.setHours(hours );
        date2.setMinutes(minutes);
        //alert(date2);

        var diff = date2.getTime() - date1.getTime();

        var hours = Math.floor(diff / (1000 * 60 * 60));
        diff -= hours * (1000 * 60 * 60);

        var mins = Math.floor(diff / (1000 * 60));
        diff -= mins * (1000 * 60);

        return {hrs: hours, mins: mins};
    }