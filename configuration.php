<?php
$ciphering = "AES-256-OFB"; //Was using AES-256-CBC but on certain time request from Firefox doesn't decrypt because of some padding issue idiot-
$options = 0;
$secret_iv = "1234567890123456";//Add your Encryption iv here //CHANGE ME
$secret_key = "abcdefghijklmnopabcdefghijklmnop";//Add your Encryption Key Here // CHANGE ME
$key = hash('sha256', $secret_key);//meh
$iv = substr(hash('sha256', $secret_iv), 0, 16);//meh

$filename = "a744d9a1f4f287e51d681fdea6c10c0aec21773969db86f29dae0b31b0357763";//Name of your File to store encrypted data in (Security Through Obscurity) //CHANGE ME
$filesize = "1000000000"; //1 GB file size limit in Bytes

//Your UserName and Password to login
$username = 'admin';  //CHANGE ME
$password = '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918'; //SHA256 Hash of admin //CHANGE ME

//SomeRandom strings for Session generation
$random1 = 'pgRETaOKZO'; //CHANGE ME
$random2 = '79qlMPqv1H'; //CHANGE ME


date_default_timezone_set('Asia/Karachi'); //set your time zone

$refresh=60; //Set page refresh time(in Seconds) to get new entries automagically I wanted to make it dynamic but ain't nobody got time for that-