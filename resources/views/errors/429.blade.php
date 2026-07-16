@extends('errors::layout')

@section('title', 'Trop de requêtes')
@section('code', '429')
@section('icon', 'bi bi-exclamation-octagon')
@section('message', 'Vous avez effectué trop de requêtes. Veuillez patienter quelques instants.')
