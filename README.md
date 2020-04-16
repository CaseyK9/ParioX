# ParioX (BETA 0.1)
ParioX is a php-written uploading, viewing and downloading tool for images, videos and files. Written to work for ShareX, but supports any POST request through form-data!

# Known issues/TODO:
- Create an actual front page and not a dummy bootstrap layout
- Clean up the view page a bit
- the installation needs to be reworked and is a jquery mess, but works!
- Clean up the admin page
- Create roles for users and admins, now every user created has the power to delete other users.
- Create chunked upload system to allow for large file uploads, and prevent issues with php's max file size.

#FAQ 
Q: I cannot upload a file that's larger than X megabytes!
A: ParioX's max file upload size is governed by the php max file size, and the php max post size settings in php.ini. 
# Why ParioX?
I wrote ParioX as an alternative to https://github.com/Pips801/UploadX, it work, but only for images and it was quite a glitchy mess. Instead of rewriting it, i wrote ParioX from scratch from barebones PHP and 0 libaries. Just vanilla PHP, javascript and bootstrap as frontend keep ParioX running.

# So why not use streamable for video, and imgur to share images?
The vast amount of upload services compitable with ShareX(out of the box) either have shady agreements that make you give away all your rights to whatever you upload! Besides this, some delete your images if they don't get enough, or too many views. ParioX runs on your own webserver meaning whatever you upload is yours to keep and delete.

# But webservers and storage are expensive!
I currently pay 9 euro's a month for 200gb of storage on a VPS, and there's much better options for storage out there. Some shared hosting providers also provide 'unlimited' storage so get yourself a reputable host with a lot of storage options and you've got a great backup server for all your screenshots and clips you want to share.


# Setup 
1. Clone the repository or download the files to your root folder of your website or a subdomain. Installing in a folder is not recommended!

2. Ensure either: your database user has privilges to create databases, or if not you have created a database in advance. ParioX will attempt to create a database in the installation walktrough, or insert tables if it's unable to create it(if it has no permissions, or if the database already exists)

3. navigate to your (sub)domain where you installed the ParioX files.

4. Setup the database settings in the installation menu
5. Setup the website settings in the installation menu
6. Create a first user in the installation menu
7. Delete the 'install' folder from your Pariox installation directory.

# Usage
1. Navigate to your pariox installation
2. Login using the credentials you created
3. go to utilities > users
4. download the pre-made sxcu file for ShareX, and open this with ShareX.
5. Open this file with ShareX and confirm the promt

![step5](https://images.mgroeneveld.nl/images/6495d096.png)

6. Ensure installation was successful by testing an upload in the prompt that appears after step 5.
![step6}(https://images.mgroeneveld.nl/images/2cef102a.png)
# Example .sxcu file
```


    {
  "Version": "13.0.1",
  "Name": "Pariox",
  "DestinationType": "ImageUploader, TextUploader, FileUploader",
  "RequestMethod": "POST",
  "RequestURL": "http://yourdomain.com/upload.php",
  "Parameters": {
    "key": "YOURAPIKEY"
  },
  "Body": "MultipartFormData",
  "FileFormName": "file"
}
    
    

```
