<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Profile
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h4 class="mb-4">My Profile</h4>

<div class="card card-custom p-4">

    <div class="text-center mb-4">
        <img id="preview" src="https://raw.githubusercontent.com/FauzanNizam/image/main/user.png" class="profile-img">
    </div>

    <form>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Nama</label>
                <input type="text" class="form-control" value="Fauzan Nizam" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" class="form-control" value="fznnzm@gmail.com" disabled>
            </div>

            <div class="col-md-6 mb-3">
                <label>Alamat</label>
                <input type="text" class="form-control" value="Jl.M.Hatta Handil 3" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label>No. Telepon</label>
                <input type="text" class="form-control" value="081255101063" readonly>
            </div>

            <div class="col-md-12 mb-3">
                <label>Tanggal Lahir</label>
                <input type="text" class="form-control" value="2006-07-01" readonly>
            </div>

            <div class="col-md-12 mb-3">
                <label>Upload Foto</label>
                <input type="file" class="form-control" onchange="previewImage(event)">
            </div>
        </div>

        <button type="button" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<script>
function previewImage(event){
    const input = event.target;
    const preview = document.getElementById('preview');

    if(input.files && input.files[0]){
        const reader = new FileReader();

        reader.onload = function(e){
            preview.src = e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?= $this->endSection() ?>