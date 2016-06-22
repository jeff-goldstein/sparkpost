Retail: This template demonstrates the following capabilities
   This template is a very advanced template with a lot of capabilities meant to show how flexible SparkPost templates can be.  There 
   is a lot of flexibility built in to allow for White Labelling, Picture size modification, any number of products per row and any
   number of rows.
   o Array's within Arrays.  The top level are rows, the next level are columns (products) within the rows
   o Setting Link Names
   o Unsubscribe Link defined
   o Conditional Statements
   o css embedding that uses substitution variables
   o Dynamic html that allows for substitutions within substitution variables
   o White Labelling
   o Mulitple part HTML substitution (each product can have personalized URL parameters for each user)
   
   
   There are three examples in this folder as of 6/22/2016; Electronics, Outdoor and Fashion.  Each of these use various company
   products that I found in order to demonstrate the template.  Please keep in mind, some of the URL's may not be working given
   obsolescence of retail products.
   
   Details:
   While all of the data is sample data, here is a glossary of the data fields:
     Link_format: Used to in some of the dynamic_html code sniplets.  This allows you to change the style of those entries in one location.
     Dyanmic HTML Fields:
        o next1 & next3:  These html codes sniplets are used toward the bottom of the email.  The names do not have to be in sequence,
          they are simply referenced in an array in order to tell the system, which items to loop through.  In this example, there is
          a global variable, "default_news" that is used if a local array called "news" is not present in the users personal json data
          section.
        o job1-5: This is similar to the 'next' fields.  These jobs are listed at the bottom of the email.  Each user can have their own
          array denoting which jobs to present; otherwise the default array will be used.  This is a good example of using conditional
          statements to make sure something is being displayed.
        o product arrary: Each product is identified with a unique id number (the id only has to be unique in the json in order to
          identify it).  Each product has multiple fields that are generic across anyone who would recieve the product in their email.
          The template will merge the product.url field with the users url field to create a personalized link for each product.  If the
          personalized URL does not exist, the product link simply won't be personalized but should work.  In the Postman samples, some
          of the user profile URL entries have been used, while others are either blank for missing all together.  This is done on 
          purpose to show the flexibility of the templates and the templating engine.
        o In order to allow for different size and shapes for the pictures, each add can set what it's size is that should be used.  
          There is an override variable that is checked first and will be used for all pictures of that type.  If that override
          variable does not exist, the system will use the specific product size variables.  If you want the product sizes to be the
          default, then change the logic in the template.
          
   Since much of the data resides in the dynamic html json section, the render_dynamic_content macro
   is used a lot. (i.e.  {{render_dynamic_content(dynamic_html )  Because the product data is not using html code and/or 
   substitutions within substitution varialbes, this data can be removed from the dynamic content section.  That removes the need
   for the render_dynamic_content macro for the products.
