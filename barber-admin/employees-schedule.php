<?php
    session_start();

    //Titre de la page
    $pageTitle = 'Planning des employés';

    //Includes
    include 'connect.php';
    include 'Includes/functions/functions.php'; 
    include 'Includes/templates/header.php';

    //Fichiers JS supplémentaires
    echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";

    //Vérifie si l'utilisateur est déjà connecté
    if(isset($_SESSION['username_barbershop_Xw211qAAsq4']) && isset($_SESSION['password_barbershop_Xw211qAAsq4']))
    {
?>
        <!-- Début du contenu de la page -->
        <div class="container-fluid">
    
            <!-- En-tête de la page -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Planning des employés</h1>
        
            </div>

            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Planning des employés</h6>
                </div>
                <div class="card-body">
                    <div class="sb-entity-selector" style="max-width:300px;">
                        <form action="employees-schedule.php" method="POST">
                            <div class="form-group">
                                <label class="control-label" for="emloyee_schedule_select">
                                    Sélectionnez l'employé pour configurer l'horaire :
                                </label>
                                <div style="display:inline-block;margin-bottom: 10px;">
                                    <?php 
                                        $stmt = $con->prepare('select * from employees');
                                        $stmt->execute();
                                        $employees = $stmt->fetchAll();
                                    
                                        echo "<select class='form-control' name='employee_selected'>";
                                            foreach ($employees as $employee) 
                                            {
                                                echo "<option value=".$employee['employee_id']." ".((isset($_POST['employee_selected']) && $_POST['employee_selected'] == $employee['employee_id'])?'selected':'').">".$employee['first_name']." ".$employee['last_name']."</option>";
                                            }
                                        echo "</select>";                                    
                                    ?>
                                </div>
                                <button type="submit" name="show_schedule_sbmt" class="btn btn-primary">Afficher l'horaire</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="alert alert-info">
                        Configurez vos paramètres de semaine ici. Sélectionnez simplement l'heure de début et l'heure de fin pour configurer les heures de travail des employés.
                    </div>
                    
                    
                    <!-- PARTIE PLANNING -->
                    
                    <div class="sb-content" style="min-height: 500px;">
                        <?php

                            /** QUAND LE BOUTON AFFICHER L'HORAIRE EST CLIQUÉ **/

                            if(isset($_POST['show_schedule_sbmt']))
                            {
                        ?>
                                <form method="POST" action="employees-schedule.php">
                                    <input type="hidden" name="employee_id" value="<?php echo $_POST['employee_selected'];?>" hidden>     
                                    <div class="worktime-days">
                                        <?php
                                            $employee_id = $_POST['employee_selected'];
                                            $stmt = $con->prepare('select * from employees e, employees_schedule es where es.employee_id = e.employee_id and e.employee_id = ?');
                                            $stmt->execute(array($employee_id));
                                            $employees = $stmt->fetchAll();
            
                                            $days = array("1"=>"Lundi",
                                                "2"=>"Mardi",
                                                "3"=>"Mercredi",
                                                "4"=>"Jeudi",
                                                "5"=>"Vendredi",
                                                "6"=>"Samedi",
                                                "7"=>"Dimanche");
                                        
                                            //Jours disponibles
                                            $jours_disponibles = array();
                                            foreach($employees as $employee)
                                            {
                                                $jours_disponibles[] = $employee['day_id'];
                                            }
                                        
                                            foreach($days as $key => $value)
                                            {
                                                echo "<div class='worktime-day row'>";
                                                
                                                if(in_array($key, $jours_disponibles))
                                                {
                                                    echo "<div class='form-group col-md-4'>";
                                                        echo "<input name='".$value."' id='".$key."' class='sb-worktime-day-switch' type='checkbox' checked>";
                                                        echo "<span class='day-name'>";                
                                                            echo $value;
                                                        echo "</span>";
                                                    echo "</div>";
                                                    
                                                    foreach($employees as $employee)
                                                    {
                                                        if(in_array($key, $jours_disponibles) && $employee['day_id'] == $key)
                                                        {
                                                            echo "<div class='time_ col-md-8 row'>";
                                                            echo "<div class='form-group col-md-6'>";
                                                            echo "<input type='time' name='".$value."-from' value='".$employee['from_hour']."' class='form-control'>";
                                                            echo "</div>";
                                                            echo "<div class='form-group col-md-6'>";
                                                            echo "<input type='time' name='".$value."-to' value='".$employee['to_hour']."' class='form-control'>";
                                                            echo "</div>";
                                                            echo "</div>";
                                                        
                                                        }
                                                    
                                                    }
                                                }
                                                else
                                                {
                                                    echo "<div class='form-group col-md-4'>";
                                                    echo "<input name='".$value."' id='".$key."' class='sb-worktime-day-switch' type='checkbox'>";
                                                    echo "<span class='day-name'>";                
                                                    echo $value;
                                                    echo "</span>";
                                                    echo "</div>";
                                                    
                                                    echo "<div class='time_ col-md-8 row' style='display:none;'>";
                                                    echo "<div class='form-group col-md-6'>";
                                                    echo "<input type='time' name='".$value."-from' value='09:00' class='form-control'>";
                                                    echo "</div>";
                                                    echo "<div class='form-group col-md-6'>";
                                                    echo "<input type='time' name='".$value."-to' value='18:00' class='form-control'>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                    
                                                }
                                                
                                                echo "</div>";
                                            }
                                        ?>
                                    </div>

                                    <!-- BOUTON ENREGISTRER L'HORAIRE -->

                                    <div class="form-group">
                                        <button type="submit" name="save_schedule_sbmt" class="btn btn-info">Enregistrer l'horaire</button>
                                    </div>
                                </form>
                        <?php
                            }
                        ?>
                    </div>

                    <?php

                        /** QUAND LE BOUTON ENREGISTRER L'HORAIRE EST CLIQUÉ **/

                        if(isset($_POST['save_schedule_sbmt']))
                        {
                            $days = array("1"=>"Lundi",
                               "2"=>"Mardi",
                               "3"=>"Mercredi",
                               "4"=>"Jeudi",
                               "5"=>"Vendredi",
                               "6"=>"Samedi",
                               "7"=>"Dimanche");
                            $stmt = $con->prepare("delete from employees_schedule where employee_id = ?");
                            $stmt->execute(array($_POST['employee_id']));
                            
                            foreach($days as $key=>$value)
                            {
                                if(isset($_POST[$value]))
                                {   
                                    $stmt = $con->prepare("insert into employees_schedule(employee_id,day_id,from_hour,to_hour) values(?, ?, ?, ?)");
                                    $stmt->execute(array($_POST['employee_id'], $key, $_POST[$value.'-from'], $_POST[$value.'-to']));
                                    
                                    $message = "Vous avez mis à jour avec succès le planning de l'employé!";
                                    
                                    ?>

                                        <script type="text/javascript">
                                            swal("Définir l'horaire de l'employé","Vous avez défini avec succès l'horaire de l'employé!", "success").then((value) => {}); 
                                        </script>

                                    <?php
                                }
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
  
<?php 
        
        //Inclure le pied de page
        include 'Includes/templates/footer.php';
    }
    else
    {
        header('Location: login.php');
        exit();
    }

?>
