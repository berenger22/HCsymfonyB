$(document).ready(function() {
    $('.reponse').click(function() {
        $(this).toggleClass('reponse').toggleClass('preReponse');
    })
})
document.onmousemove = suitsouris;

function suitsouris(evenement) {
    if (navigator.appName) {
        var x = event.x + document.body.scrollLeft;
        var y = event.y + document.body.scrollTop;
    }

    document.getElementById("image_suit_souris").style.left = (x - 200) + 'px';
    document.getElementById("image_suit_souris").style.top = (y + 1) + 'px';
}

/*function openLink(link, id) {
    var form = document.createElement("form", { action: link, method: "POST" });
    form.appendChild(document.createElement("input", { name: "questionId", value: id }));
    document.body.appendChild(form);
    // form.submit;
    alert(form);
    location.href = link;


}
$(function(){
    $("form").submit(function(e) {
      e.preventDefault();
      var $form = $(this);
      $.post($form.attr("action"), $form.serialize())
      .done(function(data) {
        $("#popup").html(data);
        $("#formulaire").modal("hide"); 
      })
      .fail(function() {
        alert("ça marche pas...");
      });
    });
  });*/