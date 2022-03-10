<?php
set_time_limit(1200);

include 'koneksi.php';

session_start();
$id = $_GET['id'];
$data = mysqli_query($koneksi,"select * from template where template_kontak='$id'");
while($x = mysqli_fetch_array($data)){

$nama  = $x['template_nama'];
$profil = $x['template_profil'];
$jam = $x['template_operasional'];
$alamat = $x['template_alamat'];
$email = $x['template_email'];
$kontak = $x['template_kontak'];
$logo = $x['template_logo'];
$status = $x['template_status'];



if($nama == ""){
    header("location:owner.php?alert=gagal");
}else{



// Halaman About us //
$doc = new DOMDocument;
$doc->validateOnParse = true;
@$doc->loadHTMLFile("about.php");

$name = $doc->getElementById("nama_tempat");
$htmlNama = $doc->createTextNode($nama);
$name->appendChild($htmlNama);

$profile = $doc->getElementById("profil");
$htmlProfile = $doc->createTextNode($profil);
$profile->appendChild($htmlProfile);

$operational = $doc->getElementById("operasional");
$htmlOperational = $doc->createTextNode($jam);
$operational->appendChild($htmlOperational);

$address = $doc->getElementById("lokasi");
$htmlAddress = $doc->createTextNode($alamat);
$address->appendChild($htmlAddress);

$mail = $doc->getElementById("email");
$htmlMail = $doc->createTextNode($email);
$mail->appendChild($htmlMail);

$contact = $doc->getElementById("kontak");
$htmlContact = $doc->createTextNode($kontak);
$contact->appendChild($htmlContact);

$doc->saveHTMLFile("about.php");
// -Halaman about us- //


// Upload Logo //
rename("frontend/img/$logo", "frontend/img/logo.png");
// -Upload Logo- //


// Generate Folder Project //
$src = "../project_sport_center/";
$folder = str_replace(" ","_",$nama);
$dst = "../$folder";



function custom_copy($src, $dst) { 
  
    // open the source directory
    $dir = opendir($src); 
  
    // Make the destination directory if not exist
    @mkdir($dst); 
  
    // Loop through the files in source directory
    while( $file = readdir($dir) ) { 
  
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) 
            { 
  
                // Recursively calling custom copy function
                // for sub directory 
                custom_copy($src . '/' . $file, $dst . '/' . $file); 
  
            } 
            else { 
                copy($src . '/' . $file, $dst . '/' . $file); 
            } 
        } 
    } 
  
    closedir($dir);
} 


custom_copy($src, $dst);
// -Generate Folder Project- //


// Reset Halaman About us //

function removeChildren( &$node )
{
  $node->parentNode->replaceChild(
    $n = $node->cloneNode( false ),
    $node );

  $node = $n;
}

$doc2 = new DOMDocument;
$doc2->validateOnParse = true;
@$doc2->loadHTMLFile("about.php");


$name2 = $doc2->getElementById("nama_tempat");
$profile2 = $doc2->getElementById("profil");
$operational2 = $doc2->getElementById("operasional");
$address2 = $doc2->getElementById("lokasi");
$mail2 = $doc2->getElementById("email");
$contact2 = $doc2->getElementById("kontak");

removeChildren($name2);
removeChildren($profile2);
removeChildren($operational2);
removeChildren($address2);
removeChildren($mail2);
removeChildren($contact2);

$doc2->saveHTMLFile("about.php");
// -Reset Halaman About us- //

// Generate Random Password dan Buat Akun Admin //
function acakangkahuruf($panjang)
{
    $karakter= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
    $string = '';
    for ($i = 0; $i < $panjang; $i++) {
        $pos = rand(0, strlen($karakter)-1);
        $string .= $karakter[$pos];
    }
    return $string;
}

$password = acakangkahuruf(8);
$password2 = md5($password);

mysqli_query($koneksi, "insert into admin value(NULL,'$nama','$email','$password2',NULL)");
// -Generate Random Password dan Buat Akun Admin- //

// Kirim akun admin ke Pemilik Lapangan //
require 'PHPMailer/PHPMailerAutoload.php';
$email_pengirim = "kurotemplate@gmail.com";
$isi = "Halo $nama, <br><br> Berikut kami kirimkan informasi akun untuk login admin: <br> Username: $email <br> Password: $password <br><br><br>Best regards,<br><br><br><br>Kuro Template";
$subjek = "Informasi Akun Login Aplikasi Booking Lapangan $nama";
$email_tujuan = $email;

$mail = new PHPMailer();

$mail->IsHTML(true);    // set email format to HTML
$mail->IsSMTP();   // we are going to use SMTP
$mail->SMTPAuth   = true; // enabled SMTP authentication
$mail->SMTPSecure = "ssl";  // prefix for secure protocol to connect to the server
$mail->Host       = "smtp.gmail.com";      // setting GMail as our SMTP server
$mail->Port       = 465;                   // SMTP port to connect to GMail
$mail->Username   = $email_pengirim;  // alamat email kamu
$mail->Password   = "Bakanokuro21";            // password GMail
$mail->SetFrom($email_pengirim, 'noreply');  //Siapa yg mengirim email
$mail->Subject    = $subjek;
$mail->Body       = $isi;
$mail->AddAddress($email_tujuan);

if(!$mail->Send()) {
    echo "Eror: ".$mail->ErrorInfo;
    exit;
}else {
    echo "<div class='alert alert-success'><strong>Berhasil!</strong> Email telah berhasil dikirim.</div>";
}
// -Kirim akun admin ke Pemilik Lapangan- //

mysqli_query($koneksi,"update template set template_status='SELESAI', template_logo='logo.png' where template_email = '$email'");

unlink("../$folder/template.php");
unlink("../$folder/template_act.php");
unlink("../$folder/owner.php");
unlink("../$folder/owner_act.php");
unlink("../$folder/owner_rej.php");
unlink('frontend/img/logo.png');
    }
}

function delete_files($target){         
   if(is_dir($target)){               
      $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK 
      foreach( $files as $file ){ 
         delete_files( $file ); 
      } 
      rmdir( $target ); 
      } elseif(is_file($target)){ 
          @unlink( $target ); 
      } 
} 

$phpmailer = "../$folder/PHPMailer";
delete_files($phpmailer);

header("location:owner.php?alert=berhasil");



