<?php
/**
 * Plugin Name: Réservation de Créneau
 * Description: Plugin de réservation de créneau sur WordPress.
 * Version: 1.0
 * Author: Thomas VILLAIN
 */

// Ajouter le formulaire de réservation
function reservation_form_shortcode() {
    ob_start();
    ?>
<!-- Formulaire de réservation HTML -->
<form method="post" action="">
    <!-- Champs du formulaire -->
    <input type="text" name="user_first_name" placeholder="Prénom" required>
    <input type="text" name="user_name" placeholder="Nom" required>
    <input type="email" name="user_email" placeholder="Adresse e-mail" required>
    <input type="date" name="reservation_date" required>
    <input type="submit" name="submit_reservation" value="Réserver">
</form>


    <!-- Ajouter le script JavaScript pour le pop-up -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php
            // Si le formulaire a été soumis, affiche le pop-up
            if (isset($_POST['submit_reservation'])) {
                echo 'alert("Merci, votre réservation a bien été prise en compte !");';
            }
            ?>
        });
    </script>
    <?php
    return ob_get_clean();
}

add_shortcode('reservation_form', 'reservation_form_shortcode');

// Gérer la réservation
function process_reservation() {
    if (isset($_POST['submit_reservation'])) {
        $user_first_name = sanitize_text_field($_POST['user_first_name']);
        $user_name = sanitize_text_field($_POST['user_name']);
        $user_email = sanitize_email($_POST['user_email']);
        $reservation_date = sanitize_text_field($_POST['reservation_date']);

        // Envoie un e-mail aux administrateurs
        $to = get_option('admin_email');
        $subject = 'Nouvelle réservation de créneau';
        $message = "Prénom: $user_first_name\nNom: $user_name\nE-mail: $user_email\nDate de réservation: $reservation_date";

        wp_mail($to, $subject, $message);

        // Ajoute une logique pour enregistrer la réservation dans la base de données si nécessaire
    }
}

add_action('init', 'process_reservation');
?>