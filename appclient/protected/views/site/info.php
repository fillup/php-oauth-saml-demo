<p>
    <strong>Access Token:</strong> <?php echo $access_token; ?>
</p>
<p>
    <strong>Token Information:</strong><br />
    <pre style="background-color: #d3d3d3; padding: 10px;"><?php echo print_r($token_info,true); ?></pre>
</p>
<p>
    <strong>Profile:</strong><br />
    <pre style="background-color: #d3d3d3; padding: 10px;"><?php echo print_r($profile,true); ?></pre>
</p>
<p>
    <strong>Documents:</strong><br />
    <pre style="background-color: #d3d3d3; padding: 10px;"><?php echo print_r($documents,true); ?></pre>
</p>
<p>
    <strong>Valid token, but API that requires scope I don't have:</strong><br />
<pre style="background-color: #d3d3d3; padding: 10px;"><?php echo print_r($not_allowed,true); ?></pre>
</p>
<p>
    <strong>Invalid Access Token Request:</strong><br />
    <pre style="background-color: #d3d3d3; padding: 10px;"><?php echo print_r($invalid_token,true); ?></pre>
</p>