<?php

/**
 * 1. Commencez par importer le script SQL disponible dans le dossier SQL.
 * 2. Connectez vous à la base de données blog.
 */
$server = 'localhost';
$db = 'blog';
$user = 'root';
$pwd = '';

try {
    $bdd = new PDO("mysql:host=$server;dbname=$db;charset=utf8", $user, $pwd);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $exception) {
    echo $exception->getMessage();
}

/**
 * 3. Sans utiliser les alias, effectuez une jointure de type INNER JOIN de manière à récupérer :
 *   - Les articles :
 *     * id
 *     * titre
 *     * contenu
 *     * le nom de la catégorie ( pas l'id, le nom en provenance de la table Categorie ).
 *
 * A l'aide d'une boucle, affichez chaque ligne du tableau de résultat.
 */

$request = $bdd->prepare("
    SELECT article.id, article.title, article.content, categorie.name
    FROM article
    INNER JOIN categorie ON article.category_fk = categorie.id
");

$request->execute();

echo "<pre>";
print_r($request->fetchAll());
echo "</pre><br><br>";


/**
 * 4. Réalisez la même chose que le point 3 en utilisant un maximum d'alias.
 */

$request = $bdd->prepare("
    SELECT ar.id, ar.title, ar.content, ca.name
    FROM article as ar
    INNER JOIN categorie as ca ON ar.category_fk = ca.id
");

$request->execute();

echo "<pre>";
print_r($request->fetchAll());
echo "</pre><br><br>";


/**
 * 5. Ajoutez un utilisateur dans la table utilisateur.
 *    Ajoutez des commentaires et liez un utilisateur au commentaire.
 *    Avec un LEFT JOIN, affichez tous les commentaires et liez le nom et le prénom de l'utilisateur ayant écris le comentaire.
 */

$stmt = $bdd->prepare("INSERT INTO utilisateur VALUES (null, 'Samuel', 'Coquelet', 'coquelet.samuel@mail.fr', 'SamSam')");

$stmt->execute();

$stmt = $bdd->prepare("INSERT INTO commentaire 
                            VALUES (null, 'Bonjour, voici un commentaire', 1, 1),
                                   (null , 'Mon petit commentaire ahah', 15, 2)
                            ");

$stmt->execute();

$request = $bdd->prepare("
    SELECT co.content, ut.firstName, ut.lastName
    FROM commentaire as co
    LEFT JOIN utilisateur as ut ON ut.id = co.user_fk
");

$request->execute();

echo "<pre>";
print_r($request->fetchAll());
echo "</pre><br><br>";