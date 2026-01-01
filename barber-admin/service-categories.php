<?php
    session_start();

    //Titre de la Page
    $pageTitle = 'Catégories de Service';

    //Inclusions
    include 'connect.php';
    include 'Includes/functions/functions.php'; 
    include 'Includes/templates/header.php';

    //Vérification si l'utilisateur est déjà connecté
    if(isset($_SESSION['username_barbershop_Xw211qAAsq4']) && isset($_SESSION['password_barbershop_Xw211qAAsq4'])) 
    {
?>
        <!-- Contenu de la Page -->
        <div class="container-fluid">
    
            <!-- Titre de la Page -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Catégories de Service</h1>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-download fa-sm text-white-50"></i>
                    Générer un Rapport
                </a>
            </div>

            <!-- Tableau des Catégories de Service -->
            <?php
                $stmt = $con->prepare("SELECT * FROM service_categories");
                $stmt->execute();
                $rows_categories = $stmt->fetchAll(); 
            ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Catégories de Service</h6>
                </div>
                <div class="card-body">

                    <!-- BOUTON AJOUTER UNE NOUVELLE CATÉGORIE -->
                    <button class="btn btn-success btn-sm" style="margin-bottom: 10px;" type="button" data-toggle="modal" data-target="#add_new_category" data-placement="top">
                        <i class="fa fa-plus"></i>
                        Ajouter une Catégorie
                    </button>

                    <!-- Modal d'Ajout d'une Nouvelle Catégorie -->
                    <div class="modal fade" id="add_new_category" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Ajouter une Nouvelle Catégorie</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="category_name">Nom de la Catégorie</label>
                                        <input type="text" id="category_name_input" class="form-control" placeholder="Nom de la Catégorie" name="category_name">
                                        <div class="invalid-feedback" id="required_category_name" style="display: none;">
                                            Le nom de la catégorie est requis !
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                    <button type="button" class="btn btn-info" id="add_category_bttn">Ajouter une Catégorie</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des Catégories -->
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID de la Catégorie</th>
                                    <th>Nom de la Catégorie</th>
                                    <th>Gérer</th>
                                </tr>
                            </thead> 
                            <tbody>
                                <?php
                                foreach($rows_categories as $category)
                                {
                                    echo "<tr>";
                                        echo "<td>";
                                            echo $category['category_id'];
                                        echo "</td>";
                                        echo "<td>";
                                            echo $category['category_name'];
                                        echo "</td>";
                                        echo "<td>";
                                            if(strtolower($category["category_name"]) != "uncategorized")
                                            {
                                                $delete_data = "delete_".$category["category_id"];
                                                $edit_data = "edit_".$category["category_id"];
                                            ?>
                                            <!-- BOUTONS SUPPRIMER & MODIFIER -->
                                            <ul>
                                                <li class="list-inline-item" data-toggle="tooltip" title="Modifier">
                                                    <button class="btn btn-success btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $edit_data; ?>" data-placement="top"><i class="fa fa-edit"></i></button>

                                                    <!-- Modal de Modification -->
                                                    <div class="modal fade" id="<?php echo $edit_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $edit_data; ?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Modifier la Catégorie</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label for="category_name">Nom de la Catégorie</label>
                                                                        <input type="text" class="form-control" id="<?php echo "input_category_name_".$category["category_id"]; ?>" value="<?php echo $category["category_name"]; ?>">
                                                                        <div class="invalid-feedback" id = "<?php echo "invalid_input_".$category["category_id"]; ?>">
                                                                            Le nom de la catégorie est requis.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                                    <button type="button" data-id = "<?php echo $category['category_id']; ?>" class="btn btn-success edit_category_bttn">Enregistrer</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <!---->
                                                <li class="list-inline-item" data-toggle="tooltip" title="Supprimer">
                                                    <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $delete_data; ?>" data-placement="top"><i class="fa fa-trash"></i></button>

                                                    <!-- Modal de Suppression -->
                                                    <div class="modal fade" id="<?php echo $delete_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $delete_data; ?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Supprimer la Catégorie</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Êtes-vous sûr de vouloir supprimer cette catégorie "<?php echo $category['category_name']; ?>"?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                                    <button type="button" data-id = "<?php echo $category['category_id']; ?>" class="btn btn-danger delete_category_bttn">Supprimer</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <?php
                                            }
                                        echo "</td>";
                                    echo "</tr>";
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

<?php 
        
        //Inclure le Pied de Page
        include 'Includes/templates/footer.php';
    }
    else
    {
        header('Location: login.php');
        exit();
    }

?>
