<?php
// This is just a routing file - the controller will determine which view to show based on user role
// Teachers with club ID 1 -> voting.teacher.index
// Students -> voting.student.index
// If you see this page directly, there's an issue with the controller logic
?>

@php
    // Immediate redirect to home if someone accesses this file directly
    header('Location: ' . route('home.index'));
    exit;
@endphp
