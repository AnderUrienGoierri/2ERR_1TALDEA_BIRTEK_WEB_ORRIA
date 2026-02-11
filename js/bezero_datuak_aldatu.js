$(document).ready(function () {
  $("#herria_id").on("change", function () {
    if ($(this).val() === "berria") {
      $("#herri_berria_atala").slideDown();
      $("#herria_berria").attr("required", true);
      $("#lurraldea_berria").attr("required", true);
      $("#nazioa_berria").attr("required", true);
    } else {
      $("#herri_berria_atala").slideUp();
      $("#herria_berria").removeAttr("required");
      $("#lurraldea_berria").removeAttr("required");
      $("#nazioa_berria").removeAttr("required");
    }
  });
});
