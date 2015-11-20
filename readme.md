# Website Contact Form Api - PHP Script

The application allows you to add contact form easily to your website. 

Setup a form on your website and add **API_DOMAIN/classes/ContactUs.php** as the 
form action where **API_DOMAIN** is the server domain 

Next setup a contact form with the following fields: 

* fromEmail - email address field of contact form
* fromName - client name field of contact form
* subject (optional) - subject field of contact form 
* message - message field of contact form 

**NB:** The contact form must have an id of contact-form

Finally, write add the following javascript code to handle posting the contact request to the server. 

**NB:** Jquery is required for this to work

```
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
