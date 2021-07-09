<?php

namespace controllers;

final class BlogController
{
    private $container;
    private $BASE_URL;
    public function __construct($container)
    {
        $this->container = $container; #container de injecao de dependencia
        $this->BASE_URL = $this->container['settings']['BASE_URL'];
    }
    public function incluirBlog($request, $response, array $args)
    {
        try {
            return $this->container['twig']->render($response, 'incluirBlog.html', [
                'BASE_URL' =>  $this->BASE_URL
            ]);
        } catch (\Exception $e) {
            return $this->container['twig']->render($response, 'mensagem.html', [
                'titulo'  => 'Falha no Sistema',
                'mensagem'     => $e, 'BASE_URL'     =>  $this->BASE_URL
            ]);
        }
    }
    public function gravarBlog($request, $response, array $args)
    {
        $postParams = $request->getParsedBody();
        $post = new \Post();
        $id = $postParams['id'] == ""?null:intval($postParams['id']);
        $post->setId($id);
        $post->setTitulo($postParams['titulo']);
        $post->setTexto($postParams['texto']);
        #die(var_dump($post));
        if($post->getTitulo() == "" && $post->getTexto() == "")
        {
            $flashMessages = $this->container['flashMessages'];
            $flashMessages->addMessage('tudoErrado', "sim");
            return $this->container['twig']->render($response, 'incluirBlog.html', [
                'BASE_URL' =>  $this->BASE_URL
                ,'post' => $post
            ]);
        }
        if($post->getTitulo() == "")
        {
            $flashMessages = $this->container['flashMessages'];
            $flashMessages->addMessage('tituloErrado', "sim");
            return $this->container['twig']->render($response, 'incluirBlog.html', [
                'BASE_URL' =>  $this->BASE_URL
                ,'post' => $post
            ]);
        }
        if($post->getTexto() == "")
        {
            $flashMessages = $this->container['flashMessages'];
            $flashMessages->addMessage('textoErrado', "sim");
            return $this->container['twig']->render($response, 'incluirBlog.html', [
                'BASE_URL' =>  $this->BASE_URL
                ,'post' => $post
            ]);
        }
        if($post->getId() == null)
        {
            $this->container['entityManager']->merge($post);
        }
        else 
        {
            $this->container['entityManager']->persist($post);
        }
        $this->container['entityManager']->flush();
        return $this->container['twig']->render($response, 'postIncluidoComSucesso.html', [
            'BASE_URL' =>  $this->BASE_URL
        ]);
    }
    public function alterarBlog($request, $response, array $args)
    {
        try {
            if(!is_numeric($args['id']))
            {
                return $this->container['twig']->render($response, 'mensagem.html', [
                    'titulo'  => 'Post inexistente',
                    'mensagem'     => "O post requisitado não existe.", 'BASE_URL'     =>  $this->BASE_URL
                ]);
            }
            $post = $this->container['entityManager']
                ->find('Post', $args['id']);
            if($post == null)
            {
                return $this->container['twig']->render($response, 'mensagem.html', [
                    'titulo'  => 'Post inexistente',
                    'mensagem'     => "O post requisitado não existe.", 'BASE_URL'     =>  $this->BASE_URL
                ]);
            }
            return $this->container['twig']->render($response, 'alterarBlog.html', [
                'BASE_URL' =>  $this->BASE_URL
                ,'post' => $post
            ]);
        } catch (\Exception $e) {
            return $this->container['twig']->render($response, 'mensagem.html', [
                'titulo'  => 'Falha no Sistema',
                'mensagem'     => $e, 'BASE_URL'     =>  $this->BASE_URL
            ]);
        }
    }
    public function visualizarBlog($request, $response, array $args)
    {
        try {
            if(!is_numeric($args['id']))
            {
                return $this->container['twig']->render($response, 'mensagem.html', [
                    'titulo'  => 'Post inexistente',
                    'mensagem'     => "O post requisitado não existe.", 'BASE_URL'     =>  $this->BASE_URL
                ]);
            }
            $post = $this->container['entityManager']
                ->find('Post', $args['id']);
            if($post == null)
            {
                return $this->container['twig']->render($response, 'mensagem.html', [
                    'titulo'  => 'Post inexistente',
                    'mensagem'     => "O post requisitado não existe.", 'BASE_URL'     =>  $this->BASE_URL
                ]);
            }
            return $this->container['twig']->render($response, 'visualizarBlog.html', [
                'BASE_URL' =>  $this->BASE_URL
                ,'post' => $post
            ]);
        } catch (\Exception $e) {
            return $this->container['twig']->render($response, 'mensagem.html', [
                'titulo'  => 'Falha no Sistema',
                'mensagem'     => $e, 'BASE_URL'     =>  $this->BASE_URL
            ]);
        }
    }
    public function confirmarApagarBlog($request, $response, array $args)
    {
        
        try {
            if(!is_numeric($args['id']))
            {
                return $this->container['twig']->render($response, 'mensagem.html', [
                    'titulo'  => 'Post inexistente',
                    'mensagem'     => "O post requisitado não existe.", 'BASE_URL'     =>  $this->BASE_URL
                ]);
            }
            $post = $this->container['entityManager']
                ->find('Post', $args['id']);
            if($post == null)
            {
                return $this->container['twig']->render($response, 'mensagem.html', [
                    'titulo'  => 'Post inexistente',
                    'mensagem'     => "O post requisitado não existe.", 'BASE_URL'     =>  $this->BASE_URL
                ]);
            }
            return $this->container['twig']->render($response, 'confirmarApagarBlog.html', [
                'BASE_URL' =>  $this->BASE_URL
                ,'post' => $post
            ]);
        } catch (\Exception $e) {
            return $this->container['twig']->render($response, 'mensagem.html', [
                'titulo'  => 'Falha no Sistema',
                'mensagem'     => $e, 'BASE_URL'     =>  $this->BASE_URL
            ]);
        }
    }
    public function listarBlogs($request, $response, array $args)
    {
        try {
            $entityManager = $this->container['entityManager'];
            $posts = $entityManager->getRepository('Post')->findAll();
            return $this->container['twig']->render($response, 'blog\listarblogs.html', [
                'BASE_URL' =>  $this->BASE_URL
                ,'temPosts' => count($posts) > 0
                ,'posts' => $posts
            ]);
        } catch (\Exception $e) {
            return $this->container['twig']->render($response, 'mensagem.html', [
                'titulo'  => 'Falha no Sistema',
                'mensagem'     => $e, 'BASE_URL'     =>  $this->BASE_URL
                
            ]);
        }
    }
    public function gravarComentario($request, $response, array $args)
    {
        $postParams = $request->getParsedBody();
        $comentario = new \Comentario();
        $comentario->setPostId($postParams['postId']);
        $comentario->setComentario($postParams['comentario']);
        if($comentario->getComentario() == "")
        {
            $flashMessages = $this->container['flashMessages'];
            $flashMessages->addMessage('comentarioErrado', "sim");
            return $response->withRedirect('/visualizarblog/'. $comentario->getPostId(), 301);
        }
        $entityManagar = $this->container['entityManager'];
        $post = $entityManagar->find('Post', $comentario->getPostId());
        $post->getComentarios()->add($comentario);
        $comentario->setPost($post);
        $entityManagar->persist($comentario);
        $entityManagar->flush();
        return $response->withRedirect('/visualizarblog/'. $comentario->getPostId(), 301);
    }
    public function apagarPost($request, $response, array $args)
    {
        try {
            $postParams = $request->getParsedBody();
            if(!is_numeric($postParams['id']))
            {
                return $this->container['twig']->render($response, 'mensagem.html', [
                    'titulo'  => 'Post inexistente',
                    'mensagem'     => "O post requisitado não existe.", 'BASE_URL'     =>  $this->BASE_URL
                ]);
            }
            $post = $this->container['entityManager']
                ->find('Post', $postParams['id']);
            if($post == null)
            {
                return $this->container['twig']->render($response, 'mensagem.html', [
                    'titulo'  => 'Post inexistente',
                    'mensagem'     => "O post requisitado não existe.", 'BASE_URL'     =>  $this->BASE_URL
                ]);
            }
            $this->container['entityManager']->remove($post);
            $this->container['entityManager']->flush();
            return $response->withRedirect('/listarblog', 301);
        } catch (\Exception $e) {
            return $this->container['twig']->render($response, 'mensagem.html', [
                'titulo'  => 'Falha no Sistema',
                'mensagem'     => $e, 'BASE_URL'     =>  $this->BASE_URL
            ]);
        }
    }
}
