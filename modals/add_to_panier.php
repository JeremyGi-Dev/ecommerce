<!-- On récupére le prix et l'id du produit -->
<script type="text/javascript">
$('.add-to-panier').on('click', function(){
  var Id = $(this).data('id');
  var Prix = $(this).data('val');
  $("#produit_id_modal").val(Id);
  $("#produit_prix_modal").val(Prix);
});
</script>

<!-- Modal -->
<div class="modal fade" id="addToPanier" tabindex="-1" role="dialog" aria-labelledby="ajout_panier" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajout_panier">Ajouter au panier</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
        <div class="modal-body">
            <!-- form -->
            <form role="form" action="<?=htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="ajout_panier" value="1" />
                <input type="hidden" id="produit_prix_modal" name="produit_prix" value="">
                <input type="hidden" id="produit_id_modal" name="produit_id" value="">
                <div class="form-group">
                    <select name="produit_quantite" id="quantity-select">
                    <option value="0">Choissisez la quantité</option>
                    <?php foreach($t_quantite as $id => $value) : ?>
                        <option value="<?=$value?>"><?=$value?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-success">Ajouter</button>
        </div>
            </form>
    </div>
  </div>
</div>