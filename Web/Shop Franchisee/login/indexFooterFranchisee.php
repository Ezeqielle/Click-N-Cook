</div>
<footer>
    <div class="footer">
        <div class="text">
            <?php
            echo '<p class="copyright">Click\'N Cook © ' . date('Y')
            ?>
            <font style="font-size: 15px; font-weight: 600" >
                |
                <a style="font-size: 15px" href="../WebGL/examples/threeJs.html">A propos</a>
                |
                <a style="font-size: 15px" href="../login/index.php">Espace Particulier</a>
            </font>
            </p>
        </div>
        <div class="langs">
            <form method="POST" class="lang">
                <?php
                if($_SESSION['lang'] == 'EN') {
                    echo '<button name="lang" class="btn btn-default btn-sm button-lang" title="Change language">';
                    echo '<span class="language"><strong>' . $_SESSION['lang'] . '</strong></span>';
                    echo '</button>';
                } else {
                    echo '<button name="lang" class="btn btn-default btn-sm button-lang" title="Changer de langue">';
                    echo '<span class="language"><strong>' . $_SESSION['lang'] . '</strong></span>';
                    echo '</button>';
                }
                ?>
            </form>
        </div>
    </div>

</footer>
<script>window.jQuery || document.write('<script src="../bootstrap/docs/assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="../bootstrap/docs/dist/js/bootstrap.min.js"></script>
<script src="../bootstrap/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
</body>
</html>