window.onload =  function () {

        $('button[data-stat="1"]').text("Franchisee");
        $('button[data-stat="1"]').toggleClass("btn-succes btn-dark");
}

$(".delete").click(function(){
    var id = $(this).parents("tr").attr("id");

    if(confirm("Delete user ?")){
        $.ajax({
            url: '/assets/delete.php',
            type: 'GET',
            data: {id:id},
            error: function () {
                alert('Failed');
            },
            success: function () {
                $("#"+id).remove();
                alert("Delete successfully");
            }
        });
    }
});

$(".statut").click(function () {
    //get statut
    var getSTATUT = $(this).attr('data-stat');
    var stat = (getSTATUT == '0' ? '1' : '0');

    //get user id
    var id = $(this).parents("tr").attr("id");

    $.ajax({
        url: '/assets/setAdmin.php',
        type: 'POST',
        data: {id:id,status:stat},
        error: function () {
            alert('Failed');
        },
        success: function () {
            $(".stat"+id).attr("data-stat",stat);
            $(".stat"+id).toggleClass("btn-success btn-dark");
            $(".stat"+id).text($(".stat"+id).text() == 'Franchisee'?'Franchisee':'admin');
        }
    })

});