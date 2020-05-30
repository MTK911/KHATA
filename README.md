<p align="center">
<img src="https://i.ibb.co/NFpgqNg/1ey.png" width="256" height="164">
</p>

# Khata what?
Khata is a utility designed to keep records of all incoming HTTP requests in secure fashion. Khata is designed while keeping usability, security and ease of deployment in mind, So Khata is database free and only need few minutes to setup and starts working.

# Khata why?
While working on my [Attiny85](https://github.com/MTK911/Attiny85) scripts I mostly rely on weebhook.site which is very useful and reliable but it's not mine. So, I always wanted to create something similar and one day I just sat and started working on it and piece by piece it turned into a complete project. So now I have my own webhook for my Attiny85 script but I can also use it to detect Cross Site Scripting (XSS) and Server-Side Request Forgery (SSRF) vulnerabilities.

# Khata how?
Khata is written in PHP (Yes, I said PHP) because PHP is easily available on all hosting platforms and doesn’t need any fancy work. For making the utility fancy I have used Bootstrap and jQuery (it was a blast [cries inside]). To make data more user friendly I have used datatable plugin which is quite good and make work easy (I found that out after writing everything myself[stupid]). Thanks to datatable all logs can be download in CSV, XLSX, PDF format <br>

For data security I am encrypting received request with AES-256-OFB and keeping them in a plain text file (Now don't be so melodramatic). From where C2 (index.php) picks up all the data decrypts it and present it in readable form. For C2 security I have tried to keep up with the security measures like Anti-CSRF, Captcha, Security Headers, and other stuff I can't remember. (If you have any suggestions to improve security put them in suggestion box).

# Demo
http://khata.getforge.io/

## Getting Started
Deploying khata is super easy you just need to have and do few things:
1. A Server Apache/Nginx etc
2. PHP installation (Tested it on PHP>7)
3. Copy all three php files to server root directory
Done

## The necessary
1. Change Username and Password(Make sure it is SHA256) in configuration file
2. Change Key and IV in configuration file for encryption
3. Change file name in configuration file
4. Change random1 and random2 variable
5. Make sure catch.php has permission to write in directory 

## The Okay I’ll do it later
1. Change time zone in configuration file
2. Use .htaccess to restrict access to data file and configuration.php file
3. For security reason log file size is limited to 1 GB you can change it to whatever you want

## Arming
Khata can be use to detect XSS and SSRF using Khata as a listener. For XSS place Khata in script tag source <script src="http://abc.xyz/catch.php"> in case of XSS vulnerability you will be able to see a log entry in C2. For SSRF visit following [blog](https://portswigger.net/web-security/ssrf).
  
## File Facts
**index.php**: C2 where you view all data<br>
**catch.php**: Request collector where you point all your requests to<br>
**configuration.php**: It is self-explanatory<br>

## Credentials
`admin/admin`

## Before login
<img src="https://i.imgur.com/RQlIzkt.gif" alt="Login" border="0">

## After login
<img src="https://i.imgur.com/QgRGsaR.png" alt="Dash" border="0">

## DISCLAIMER
All the software/scripts/applications/things in this repository are provided as is, without warranty of any kind. Use of these software/scripts/applications/things is entirely at your own risk. Creator of these softwares/scripts/applications/things is not responsible for any direct or indirect damage to your own or defiantly someone else's property resulting from the use of these software/scripts/applications/things.

## License
MIT License

Copyright (c) 2020 Muhammad Talha Khan

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
