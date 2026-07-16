@extends('errors::layout')

@section('title', 'Erreur serveur')
@section('code', '500')
@section('icon', 'bi bi-exclamation-triangle-fill')
@section('message', 'Une erreur interne est survenue. Veuillez réessayer plus tard.')
