Invoice:  This template demonstrates the following capabilities
   o Array's within Arrays.  The top level is the high level product description, the next level are specific details
   o Setting Link Names
   o Simple substitution
   o White Labelling
   o Setting Click tracking at the link level
   o Dynamic HTML (added 6/24/2016)
   
6/24/16
Jeff Goldstein: Added showing offers at the bottom of the invoice.  This demonstrates very complex Dynamic_HTML code blocks.  Since this is leveraging Dynamic_HTML, the template can demonstrate different offers to different users depending on which offers are listed in the 'offers' array within the recipients substitution_data section.  To turn this functionality off, simply don't have the 'offers' array in the substitution data area.  The template looks for that field before processing to give the template more flexibility. 
