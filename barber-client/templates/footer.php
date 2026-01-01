	
		</div>
	  	<!-- End of Main Content -->

	  	<!-- Footer -->
	  	<footer class="sticky-footer bg-white">
			<div class="container my-auto">
		  		<div class="copyright text-center my-auto">
					<span>Copyright &copy; Barbershop Website by ISGA Students</span>
		  		</div>
			</div>
	  	</footer>
	  	<!-- End of Footer -->

	</div>
	<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
		  		<h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
		  		<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
		  		</button>
			</div>
			<div class="modal-body">
				Sélectionnez "Déconnexion" ci-dessous si vous êtes prêt à terminer votre session actuelle.
			</div>
			<div class="modal-footer">
		  		<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
		  		<a class="btn btn-primary" href="logout.php">Logout</a>
			</div>
	  	</div>
	</div>
</div>

	<!-- INCLUDE JS SCRIPTS -->
	<script src="jquery.min.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script src="bootstrap.bundle.min.js"></script>
	<script src="sb-admin-2.min.js"></script>
	<script src="alls.js"></script>
	<script src="main.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>

        function openTab(evt, tabName) 
{
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    
    for (i = 0; i < tabcontent.length; i++) 
    {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName("tablinks");

    for (i = 0; i < tablinks.length; i++) 
    {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    
    document.getElementById(tabName).style.display = "table";
    evt.currentTarget.className += " active";
}
    });
   
</script>
</body>

</html>

