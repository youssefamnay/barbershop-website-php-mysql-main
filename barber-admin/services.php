<?php
    ob_start();
    session_start();

    // Titre de la page
    $pageTitle = 'Services';

    // Inclusions
    include 'connect.php';
    include 'Includes/functions/functions.php'; 
    include 'Includes/templates/header.php';

    // Fichiers JS supplémentaires
    echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";

    // Vérification si l'utilisateur est déjà connecté
    if(isset($_SESSION['username_barbershop_Xw211qAAsq4']) && isset($_SESSION['password_barbershop_Xw211qAAsq4']))
    {
?>
        <!-- Début du contenu de la page -->
        <div class="container-fluid">
    
            <!-- Titre de la page -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Services</h1>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-download fa-sm text-white-50"></i>
                    Générer un rapport
                </a>
            </div>
            
            <?php
                $do = '';

                if(isset($_GET['do']) && in_array($_GET['do'], array('Add','Edit')))
                {
                    $do = htmlspecialchars($_GET['do']);
                }
                else
                {
                    $do = 'Manage';
                }

                if($do == 'Manage')
                {
                    $stmt = $con->prepare("SELECT * FROM services s, service_categories sc where s.category_id = sc.category_id");
                    $stmt->execute();
                    $rows_services = $stmt->fetchAll();
                ?>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Services</h6>
                        </div>
                        <div class="card-body">

                            <!-- BOUTON AJOUTER UN NOUVEAU SERVICE -->
                            
                            <a href="services.php?do=Add" class="btn btn-success btn-sm" style="margin-bottom: 10px;">
                                <i class="fa fa-plus"></i> 
                                Ajouter un service
                            </a>

                            <!-- TABLEAU DES SERVICES -->

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Nom du service</th>
                                        <th scope="col">Catégorie de service</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Prix</th>
                                        <th scope="col">Durée</th>
                                        <th scope="col">Gérer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($rows_services as $service)
                                        {
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo $service['service_name'];
                                                echo "</td>";
                                                echo "<td>";
                                                    echo $service['category_name'];
                                                echo "</td>";
                                                echo "<td style = 'width:30%'>";
                                                    echo $service['service_description'];
                                                echo "</td>";
                                                echo "<td>";
                                                    echo $service['service_price'];
                                                echo "</td>";
                                                echo "<td>";
                                                    echo $service['service_duration'];
                                                echo "</td>";
                                                echo "<td>";
                                                    $delete_data = "delete_".$service["service_id"];
                                                    ?>
                                                        <ul class="list-inline m-0">

                                                            <!-- BOUTON MODIFIER -->

                                                            <li class="list-inline-item" data-toggle="tooltip" title="Modifier">
                                                                <button class="btn btn-success btn-sm rounded-0">
                                                                    <a href="services.php?do=Edit&service_id=<?php echo $service['service_id']; ?>" style="color: white;">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>
                                                                </button>
                                                            </li>

                                                            <!-- BOUTON SUPPRIMER -->

                                                            <li class="list-inline-item" data-toggle="tooltip" title="Supprimer">
                                                                <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $delete_data; ?>" data-placement="top"><i class="fa fa-trash"></i></button>

                                                                <!-- Modal de suppression -->

                                                                <div class="modal fade" id="<?php echo $delete_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $delete_data; ?>" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Supprimer le service</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">×</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                Êtes-vous sûr de vouloir supprimer ce service "<?php echo $service['service_name']; ?>"?
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                                                <button type="button" data-id = "<?php echo $service['service_id']; ?>" class="btn btn-danger delete_service_bttn">Supprimer</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    <?php
                                                echo "</td>";
                                            echo "</tr>";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                }
                elseif($do == 'Add')
                {
                    ?>
                    
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Ajouter un nouveau service</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="services.php?do=Add">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="service_name">Nom du service</label>
                                            <input type="text" class="form-control" value="<?php echo (isset($_POST['service_name']))?htmlspecialchars($_POST['service_name']):'' ?>" placeholder="Nom du service" name="service_name">
                                            <?php
                                                $flag_add_service_form = 0;
                                                if(isset($_POST['add_new_service']))
                                                {
                                                    if(empty(test_input($_POST['service_name'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Le nom du service est requis.
                                                            </div>
                                                        <?php

                                                        $flag_add_service_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <?php
                                            $stmt = $con->prepare("SELECT * FROM service_categories");
                                            $stmt->execute();
                                            $rows_categories = $stmt->fetchAll();
                                        ?>
                                        <div class="form-group">
                                            <label for="service_category">Catégorie de service</label>
                                            <select class="custom-select" name="service_category">
                                                <?php
                                                    foreach($rows_categories as $category)
                                                    {
                                                        echo "<option value = '".$category['category_id']."'>";
                                                            echo $category['category_name'];
                                                        echo "</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="service_duration">Durée du service (min)</label>
                                            <input type="text" class="form-control" value="<?php echo (isset($_POST['service_duration']))?htmlspecialchars($_POST['service_duration']):'' ?>" placeholder="Durée du service" name="service_duration">
                                            <?php

                                                if(isset($_POST['add_new_service']))
                                                {
                                                    if(empty(test_input($_POST['service_duration'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                La durée du service est requise.
                                                            </div>
                                                        <?php

                                                        $flag_add_service_form = 1;
                                                    }
                                                    elseif(!ctype_digit(test_input($_POST['service_duration'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Durée invalide.
                                                            </div>
                                                        <?php

                                                        $flag_add_service_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="service_price">Prix du service ($)</label>
                                            <input type="text" class="form-control" value="<?php echo (isset($_POST['service_price']))?htmlspecialchars($_POST['service_price']):'' ?>" placeholder="Prix du service" name="service_price">
                                            <?php

                                                if(isset($_POST['add_new_service']))
                                                {
                                                    if(empty(test_input($_POST['service_price'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Le prix du service est requis.
                                                            </div>
                                                        <?php

                                                        $flag_add_service_form = 1;
                                                    }
                                                    elseif(!is_numeric(test_input($_POST['service_price'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Prix invalide.
                                                            </div>
                                                        <?php

                                                        $flag_add_service_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="service_description">Description du service</label>
                                            <textarea class="form-control" name="service_description" style="resize: none;"><?php echo (isset($_POST['service_description']))?htmlspecialchars($_POST['service_description']):''; ?></textarea>
                                            <?php

                                                if(isset($_POST['add_new_service']))
                                                {
                                                    if(empty(test_input($_POST['service_description'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                La description du service est requise.
                                                            </div>
                                                        <?php

                                                        $flag_add_service_form = 1;
                                                    }
                                                    elseif(strlen(test_input($_POST['service_description'])) > 250)
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                La longueur de la description doit être inférieure à 250 lettres.
                                                            </div>
                                                        <?php

                                                        $flag_add_service_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- BOUTON SOUMETTRE -->

                                <button type="submit" name="add_new_service" class="btn btn-primary">Ajouter le service</button>

                            </form>

                            <?php

                                /*** AJOUTER UN NOUVEAU SERVICE ***/
                                if(isset($_POST['add_new_service']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_add_service_form == 0)
                                {
                                    $service_name = test_input($_POST['service_name']);
                                    $service_category = $_POST['service_category'];
                                    $service_duration = test_input($_POST['service_duration']);
                                    $service_price = test_input($_POST['service_price']);
                                    $service_description = test_input($_POST['service_description']);

                                    try
                                    {
                                        $stmt = $con->prepare("insert into services(service_name,service_description,service_price,service_duration,category_id) values(?,?,?,?,?) ");
                                        $stmt->execute(array($service_name,$service_description,$service_price,$service_duration,$service_category));
                                        
                                        ?> 
                                            <!-- MESSAGE DE SUCCÈS -->

                                            <script type="text/javascript">
                                                swal("Nouveau service","Le nouveau service a été créé avec succès", "success").then((value) => 
                                                {
                                                    window.location.replace("services.php");
                                                });
                                            </script>

                                        <?php

                                    }
                                    catch(Exception $e)
                                    {
                                        echo "<div class = 'alert alert-danger' style='margin:10px 0px;'>";
                                            echo 'Erreur survenue: ' .$e->getMessage();
                                        echo "</div>";
                                    }
                                    
                                }
                            ?>
                        </div>
                    </div>


                    <?php   
                }
                elseif($do == "Edit")
                {
                    $service_id = (isset($_GET['service_id']) && is_numeric($_GET['service_id']))?intval($_GET['service_id']):0;

                    if($service_id)
                    {
                        $stmt = $con->prepare("Select * from services where service_id = ?");
                        $stmt->execute(array($service_id));
                        $service = $stmt->fetch();
                        $count = $stmt->rowCount();

                        if($count > 0)
                        {
                            ?>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Modifier le service</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="services.php?do=Edit&service_id=<?php echo $service_id; ?>">
                                        <!-- ID DU SERVICE -->
                                        <input type="hidden" name="service_id" value="<?php echo $service['service_id'];?>">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="service_name">Nom du service</label>
                                                    <input type="text" class="form-control" value="<?php echo $service['service_name'] ?>" placeholder="Nom du service" name="service_name">
                                                    <?php
                                                        $flag_edit_service_form = 0;

                                                        if(isset($_POST['edit_service_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['service_name'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        Le nom du service est requis.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_service_form = 1;
                                                            }
                                                            
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <?php
                                                    $stmt = $con->prepare("SELECT * FROM service_categories");
                                                    $stmt->execute();
                                                    $rows_categories = $stmt->fetchAll();
                                                ?>
                                                <div class="form-group">
                                                    <label for="service_category">Catégorie de service</label>
                                                    <select class="custom-select" name="service_category">
                                                        <?php
                                                            foreach($rows_categories as $category)
                                                            {
                                                                if($category['category_id'] == $service['category_id'])
                                                                {
                                                                    echo "<option value = '".$category['category_id']."' selected>";
                                                                        echo $category['category_name'];
                                                                    echo "</option>";
                                                                }
                                                                else
                                                                {
                                                                    echo "<option value = '".$category['category_id']."'>";
                                                                        echo $category['category_name'];
                                                                    echo "</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="service_duration">Durée du service (min)</label>
                                                    <input type="text" class="form-control" value="<?php echo $service['service_duration'] ?>" placeholder="Durée du service" name="service_duration">
                                                    <?php

                                                        if(isset($_POST['edit_service_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['service_duration'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        La durée du service est requise.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_service_form = 1;
                                                            }
                                                            elseif(!ctype_digit(test_input($_POST['service_duration'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        Durée invalide.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_service_form = 1;
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="service_price">Prix du service ($)</label>
                                                    <input type="text" class="form-control" value="<?php echo $service['service_price'] ?>" placeholder="Prix du service" name="service_price">
                                                    <?php

                                                        if(isset($_POST['edit_service_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['service_price'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        Le prix du service est requis.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_service_form = 1;
                                                            }
                                                            elseif(!is_numeric(test_input($_POST['service_price'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        Prix invalide.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_service_form = 1;
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="service_description">Description du service</label>
                                                    <textarea class="form-control" name="service_description" style="resize: none;"><?php echo $service['service_description']; ?></textarea>
                                                    <?php

                                                        if(isset($_POST['edit_service_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['service_description'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        La description du service est requise.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_service_form = 1;
                                                            }
                                                            elseif(strlen(test_input($_POST['service_description'])) > 250)
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        La longueur de la description doit être inférieure à 250 lettres.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_service_form = 1;
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- BOUTON SOUMETTRE -->
                                        <button type="submit" name="edit_service_sbmt" class="btn btn-primary">Enregistrer les modifications</button>
                                    </form>
                                    
                                    <?php
                                        /*** MODIFIER LE SERVICE ***/
                                        if(isset($_POST['edit_service_sbmt']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_edit_service_form == 0)
                                        {
                                            $service_id = $_POST['service_id'];
                                            $service_name = test_input($_POST['service_name']);
                                            $service_category = $_POST['service_category'];
                                            $service_duration = test_input($_POST['service_duration']);
                                            $service_price = test_input($_POST['service_price']);
                                            $service_description = test_input($_POST['service_description']);

                                            try
                                            {
                                                $stmt = $con->prepare("update services set service_name = ?, service_description = ?, service_price = ?, service_duration = ?, category_id = ? where service_id = ? ");
                                                $stmt->execute(array($service_name,$service_description,$service_price,$service_duration,$service_category,$service_id));
                                                
                                                ?> 
                                                    <!-- MESSAGE DE SUCCÈS -->

                                                    <script type="text/javascript">
                                                        swal("Service mis à jour","Le service a été mis à jour avec succès", "success").then((value) => 
                                                        {
                                                            window.location.replace("services.php");
                                                        });
                                                    </script>

                                                <?php

                                            }
                                            catch(Exception $e)
                                            {
                                                echo "<div class = 'alert alert-danger' style='margin:10px 0px;'>";
                                                    echo 'Erreur survenue: ' .$e->getMessage();
                                                echo "</div>";
                                            }
                                            
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                        else
                        {
                            header('Location: services.php');
                            exit();
                        }
                    }
                    else
                    {
                        header('Location: services.php');
                        exit();
                    }
                }
            ?>
        </div>
  
<?php 
        
        // Inclure le pied de page
        include 'Includes/templates/footer.php';
    }
    else
    {
        header('Location: login.php');
        exit();
    }

?>