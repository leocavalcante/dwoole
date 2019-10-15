$tags = "base", "dev", "prod"

foreach ($tag in $tags)
{
  docker build --squash -t "dwoole:$tag" ".\$tag\"
  docker tag "dwoole:$tag" "leocavalcante/dwoole:$tag"
}
