<!-- On récupére l'id du panier -->
<script type="text/javascript">
$('.delete-from-panier').on('click', function(){
  var Id = $(this).data('id');
  $("#panier_id_modal").val(Id);
});
</script>

<!-- Modal -->
<div class="modal fade" id="deleteFromPanier" tabindex="-1" role="dialog" aria-labelledby="delete_panier" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="delete_panier">Supprimer du panier</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <!-- form -->
            <form role="form" action="<?=htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="supprimer_panier" value="1" />
                    <input type="hidden" id="panier_id_modal" name="panier_id" value="">
                    <div class="form-group">
                        Vous allez supprimer cette article de votre panier, êtes-vous sûr ?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>