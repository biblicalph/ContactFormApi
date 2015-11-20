# Website Contact Form Api - PHP Script

The application allows you to add a contact form to your website. Messages sent via the contact form will be delivered to an email you provide during the setup.

### Setting up the api
The application uses Mandrill for sending emails. Setup a mandrill account and generate your **_APIKEY_**

Create a .env file in the root of this project, copy the contents of *.env.example* and edit to your specific information. 

### Setting up your contact form 
Setup a form on your website and add **DOMAIN/classes/ContactUs.php** as the 
form action where **DOMAIN** is the server domain 

Next setup a contact form as indicated below (style to your liking): 

```
<form action="DOMAIN/classes/ContactUs.php" method="post" id="contact-form" accept-charset="UTF-8">
	<label for="name">Your Name</label>
	<input type="text" name="fromName" id="name" /><br/>
	<label for="email">Your Email</label>
	<input type="text" name="fromEmail" id="email" /><br/>
	<label for="subject">Subject</label>
	<input type="text" name="subject" id="subject" /><br/>
	<label for="message">Message</label>
	<textarea name="message" id="message"></textarea><br/>
	<button type="submit">Send Message</button>
</form>
```

Finally, the following javascript code before the closing body tag of your website page. This will  handle posting the contact request to the server. 

**NB:** Jquery is required for this to work

```
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
      $("#contact-form").submit(function(event) {
        event.preventDefault();
        sendMessage(this);
      });
    });
    
    function sendMessage(obj) {
      if ($(obj).find("input[name='fromName']").val() !== "" && 
        $(obj).find("input[name='fromEmail']").val() !== "" && 
        $(obj).find("input[name='fromSubject']").val() !== "" && 
        $(obj).find("input[name='message']").val() !== "") {

        // Disable the submit button and display the loading icon
        $("#contact-submit-button").attr("disabled", true);
        $("#ajax-loader").show();

        $.post($(obj).attr("action"), $(obj).serialize(), function(data){
          $("#status").html(data.status);
          if (data.wasSent) 
              $("#status").addClass("alert alert-success");
          else 
              $("#status").addClass("alert alert-error");
          clearMessage();
          // Enable the submit button and hide the loading icon
          $("#contact-submit-button").removeAttr("disabled");
          $("#ajax-loader").hide();
        }, 'json');
      } else {
        $("#status").html("The name, email , subject and message fields are required");
        $("#status").addClass("alert alert-error");
        clearMessage();
      } 
      return false;
    }
    
    function clearMessage() {
      setTimeout(function() {
            $("#status").removeClass("alert alert-success alert-error");
            $("#status").empty();
        }, 8000);
    }
  </script>
```
